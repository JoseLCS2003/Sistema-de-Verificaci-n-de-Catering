import React, { useState, useCallback, useEffect, useMemo } from 'react';
import CameraFeed from './components/CameraFeed';
import TrayStatus from './components/TrayStatus';
import Alert from './components/Alert';
import { validateProductWithGemini } from './services/geminiService';
import { ScannedItems, AlertInfo, AlertType, Flight } from './types';
import { GeminiValidationResult } from './types';
import { PowerIcon, FlightIcon } from './components/icons';

// Mock data for multiple flights with different menus
const MOCK_FLIGHTS: Flight[] = [
  {
    id: 'FL001',
    flightNumber: 'AF2024',
    origin: 'Paris',
    destination: 'Clase Business',
    menu: {
      id: 1,
      name: 'Business Class Standard',
      menu_items: [
        { id: 1, menu_id: 1, product_id: 'COKE-001', quantity: 12, product: { id: 'COKE-001', name: 'Coca-Cola Can', category_id: 2 } },
        { id: 2, menu_id: 1, product_id: 'COKE-002', quantity: 12, product: { id: 'COKE-002', name: 'Coca-Cola Zero Can', category_id: 2 } },
        { id: 3, menu_id: 1, product_id: 'WATER-001', quantity: 8, product: { id: 'WATER-001', name: 'Mineral Water Bottle', category_id: 2 } },
        { id: 4, menu_id: 1, product_id: 'COOKIE-001', quantity: 10, product: { id: 'COOKIE-001', name: 'Principe Cookies', category_id: 1 } },
        { id: 5, menu_id: 1, product_id: 'SAND-001', quantity: 8, product: { id: 'SAND-001', name: 'Sandwich', category_id: 3 } },
      ]
    }
  },
  {
    id: 'FL002',
    flightNumber: 'IB6845',
    origin: 'Madrid',
    destination: 'New York',
    menu: {
      id: 2,
      name: 'Transatlantic Economy',
      menu_items: [
        { id: 6, menu_id: 2, product_id: 'COKE-001', quantity: 20, product: { id: 'COKE-001', name: 'Coca-Cola Can', category_id: 2 } },
        { id: 7, menu_id: 2, product_id: 'WATER-001', quantity: 15, product: { id: 'WATER-001', name: 'Mineral Water Bottle', category_id: 2 } },
        { id: 8, menu_id: 2, product_id: 'SAND-001', quantity: 18, product: { id: 'SAND-001', name: 'Sandwich', category_id: 3 } },
      ]
    }
  },
    {
    id: 'FL003',
    flightNumber: 'LH111',
    origin: 'Frankfurt',
    destination: 'London',
    menu: {
      id: 3,
      name: 'Short Haul Snack',
      menu_items: [
        { id: 9, menu_id: 3, product_id: 'WATER-001', quantity: 10, product: { id: 'WATER-001', name: 'Mineral Water Bottle', category_id: 2 } },
        { id: 10, menu_id: 3, product_id: 'COOKIE-001', quantity: 15, product: { id: 'COOKIE-001', name: 'Principe Cookies', category_id: 1 } },
      ]
    }
  }
];


function App() {
  const [flights] = useState<Flight[]>(MOCK_FLIGHTS);
  const [selectedFlightId, setSelectedFlightId] = useState<string>(flights[0].id);
  const [scannedItems, setScannedItems] = useState<ScannedItems>({});
  const [isProcessing, setIsProcessing] = useState(false);
  const [alertInfo, setAlertInfo] = useState<AlertInfo>({ type: AlertType.NONE, message: '' });
  const [isSystemActive, setIsSystemActive] = useState(true);
  
  const selectedFlight = useMemo(() => {
    return flights.find(f => f.id === selectedFlightId)!;
  }, [flights, selectedFlightId]);
  
  const menuItems = selectedFlight.menu.menu_items;

  useEffect(() => {
    let timer: ReturnType<typeof setTimeout>;
    if (alertInfo.type !== AlertType.NONE) {
      timer = setTimeout(() => setAlertInfo({ type: AlertType.NONE, message: '' }), 5000);
    }
    return () => clearTimeout(timer);
  }, [alertInfo]);


  const handleValidationResult = useCallback((result: GeminiValidationResult) => {
      if (result.isCorrectItem && result.productId) {
          const productOnList = menuItems.find(item => item.product.id === result.productId);
          if (!productOnList) {
               setAlertInfo({ type: AlertType.ERROR, message: `Error: ${result.productName} no está en la lista de empaque para este vuelo.` });
               return;
          }

          const currentCount = scannedItems[result.productId] || 0;
          if (currentCount >= productOnList.quantity) {
              setAlertInfo({ type: AlertType.INFO, message: `Todos los ${result.productName}s ya han sido empacados.` });
          } else {
              setScannedItems(prev => ({
                  ...prev,
                  [result.productId!]: (prev[result.productId!] || 0) + 1,
              }));

              let successMessage = `Agregado: ${result.productName}`;
              if (result.productId === 'WATER-001' && result.liquidPercentage !== null && result.liquidPercentage >= 0) {
                  successMessage += ` (~${Math.round(result.liquidPercentage)}% lleno)`;
              }
              setAlertInfo({ type: AlertType.SUCCESS, message: successMessage });
          }

      } else {
          setAlertInfo({ type: AlertType.ERROR, message: result.reason || "Artículo incorrecto escaneado." });
      }
  }, [menuItems, scannedItems]);

  const handleCapture = useCallback(async (imageDataUrl: string) => {
      if (isProcessing) return;

      setIsProcessing(true);
      setAlertInfo({ type: AlertType.NONE, message: '' });
      try {
        const result = await validateProductWithGemini(imageDataUrl, menuItems);
        handleValidationResult(result);
      } catch (error) {
        console.error(error);
        setAlertInfo({ type: AlertType.ERROR, message: "Error al validar el producto." });
      } finally {
        setIsProcessing(false);
      }
  }, [menuItems, handleValidationResult, isProcessing]);

  const toggleSystemActive = () => {
    setIsSystemActive(prev => !prev);
  };
  
  const handleFlightChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
      setSelectedFlightId(event.target.value);
      // Reset scanned items for the new tray
      setScannedItems({});
      setAlertInfo({ type: AlertType.INFO, message: `Cambiado al Vuelo ${flights.find(f => f.id === event.target.value)?.flightNumber}.`})
  }

  return (
    <>
      <div className="min-h-screen bg-slate-100 p-4 sm:p-6 lg:p-8">
        <header className="mb-8 max-w-7xl mx-auto">
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 className="text-4xl font-bold text-slate-800">Sistema de Verificación de Catering</h1>
                    <p className="text-lg text-slate-600 mt-1">Impulsado por IA de Gemini</p>
                </div>

                <div className="flex items-center gap-4 w-full sm:w-auto">
                     <div className="relative w-full sm:w-64">
                        <FlightIcon className="w-5 h-5 text-slate-400 absolute top-1/2 -translate-y-1/2 left-3 pointer-events-none"/>
                        <select
                            value={selectedFlightId}
                            onChange={handleFlightChange}
                            className="w-full appearance-none bg-white border border-slate-300 text-slate-700 py-2 pl-10 pr-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            {flights.map(flight => (
                                <option key={flight.id} value={flight.id}>
                                    Vuelo {flight.flightNumber} - {flight.destination}
                                </option>
                            ))}
                        </select>
                    </div>
                    <button
                        onClick={toggleSystemActive}
                        className={`flex items-center gap-2 px-4 py-2 rounded-md font-semibold text-white transition-colors ${
                        isSystemActive ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'
                        }`}
                    >
                        <PowerIcon className="w-5 h-5"/>
                        {isSystemActive ? 'Apagar Sistema' : 'Encender Sistema'}
                    </button>
                </div>
            </div>
        </header>
        
        <main className="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-7xl mx-auto">
          <div className="lg:col-span-1">
            <CameraFeed onCapture={handleCapture} isProcessing={isProcessing} isSystemActive={isSystemActive}/>
          </div>
          <div className="lg:col-span-1">
            <TrayStatus flight={selectedFlight} scannedItems={scannedItems} />
          </div>
        </main>
      </div>

      <Alert alertInfo={alertInfo} onClose={() => setAlertInfo({ type: AlertType.NONE, message: '' })} />
    </>
  );
}

// Fix: Add default export to make the component available for import in other files.
export default App;
