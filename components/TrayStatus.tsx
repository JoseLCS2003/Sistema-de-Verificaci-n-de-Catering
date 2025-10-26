import React from 'react';
// Fix: Import MenuItem to explicitly type the `item` parameter in the `reduce` function.
import { Flight, ScannedItems, MenuItem } from '../types';
import { CheckCircleIcon } from './icons';

interface TrayStatusProps {
  flight: Flight;
  scannedItems: ScannedItems;
}

const TrayStatus: React.FC<TrayStatusProps> = ({ flight, scannedItems }) => {
    
    const menuItems = flight.menu.menu_items;
    // Fix: Explicitly type the parameters of the reduce function. This ensures that `sum` and `item` are correctly typed, resolving the arithmetic operation error.
    const totalRequired = menuItems.reduce((sum: number, item: MenuItem) => sum + item.quantity, 0);
    const totalScanned = Object.values(scannedItems).reduce((sum: number, count: number) => sum + count, 0);
    const progress = totalRequired > 0 ? (totalScanned / totalRequired) * 100 : 0;
    const isComplete = menuItems.every(item => (scannedItems[item.product.id] || 0) === item.quantity);

  return (
    <div className="bg-white rounded-lg shadow-lg p-6 h-full flex flex-col">
      <h2 className="text-2xl font-bold mb-1 text-blue-800">Lista de Empaque de Bandeja</h2>
      <p className="text-lg text-slate-600 mb-6 font-semibold">
        Vuelo {flight.flightNumber} a {flight.destination}
      </p>
      
      {isComplete && (
        <div className="bg-green-100 text-green-800 p-4 rounded-lg mb-6 flex items-center">
            <CheckCircleIcon className="w-8 h-8 mr-3"/>
            <div>
                <h3 className="font-bold">¡Empaque Completo!</h3>
                <p>Esta bandeja está lista para su salida.</p>
            </div>
        </div>
      )}

      <div className="mb-4">
        <div className="flex justify-between mb-1">
            <span className="text-base font-medium text-blue-700">Progreso General</span>
            <span className="text-sm font-medium text-blue-700">{totalScanned} / {totalRequired} Artículos</span>
        </div>
        <div className="w-full bg-slate-200 rounded-full h-2.5">
            <div className="bg-blue-600 h-2.5 rounded-full" style={{ width: `${progress}%` }}></div>
        </div>
      </div>
      
      <div className="flex-grow overflow-y-auto pr-2">
        <ul className="space-y-3">
          {menuItems.map((item) => {
            const scannedCount = scannedItems[item.product.id] || 0;
            const isItemComplete = scannedCount === item.quantity;
            const isOverfilled = scannedCount > item.quantity;
            
            return (
              <li key={item.id} className={`p-4 rounded-lg transition-all duration-300 ${isItemComplete ? 'bg-green-50' : 'bg-slate-100'}`}>
                <div className="flex justify-between items-center">
                  <div>
                    <p className={`font-semibold ${isItemComplete ? 'text-green-700' : 'text-slate-800'}`}>{item.product.name}</p>
                    <p className="text-sm text-slate-500">ID de Producto: {item.product.id}</p>
                  </div>
                  <div className={`text-lg font-bold px-3 py-1 rounded-md ${
                      isOverfilled ? 'bg-red-100 text-red-700' : 
                      isItemComplete ? 'bg-green-100 text-green-700' : 
                      'bg-slate-200 text-slate-700'
                  }`}>
                    {scannedCount} / {item.quantity}
                  </div>
                </div>
              </li>
            );
          })}
        </ul>
      </div>
    </div>
  );
};

export default TrayStatus;