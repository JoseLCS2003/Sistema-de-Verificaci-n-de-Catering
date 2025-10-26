
import React, { useRef, useEffect, useState } from 'react';
import LoadingSpinner from './LoadingSpinner';
import { PowerIcon } from './icons';

interface CameraFeedProps {
  onCapture: (imageDataUrl: string) => void;
  isProcessing: boolean;
  isSystemActive: boolean;
}

const SCAN_INTERVAL_MS = 2500; // Time between automatic scans

const CameraFeed: React.FC<CameraFeedProps> = ({ onCapture, isProcessing, isSystemActive }) => {
  const videoRef = useRef<HTMLVideoElement>(null);
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [hasCamera, setHasCamera] = useState(true);
  const streamRef = useRef<MediaStream | null>(null);

  useEffect(() => {
    const startCamera = async () => {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        streamRef.current = stream;
        if (videoRef.current) {
          videoRef.current.srcObject = stream;
        }
        setHasCamera(true);
      } catch (err) {
        console.error("Error accessing camera: ", err);
        setHasCamera(false);
      }
    };

    const stopCamera = () => {
      if (streamRef.current) {
        streamRef.current.getTracks().forEach(track => track.stop());
        streamRef.current = null;
      }
      if (videoRef.current) {
        videoRef.current.srcObject = null;
      }
    };

    if (isSystemActive) {
      startCamera();
    } else {
      stopCamera();
    }

    // Cleanup function to stop camera when component unmounts
    return () => {
      stopCamera();
    };
  }, [isSystemActive]);

  useEffect(() => {
    // If we are currently processing, have no camera, or the system is off, don't scan.
    if (isProcessing || !hasCamera || !isSystemActive) {
      return;
    }

    const captureFrame = () => {
      if (!videoRef.current || !canvasRef.current) return;
      
      const video = videoRef.current;
      const canvas = canvasRef.current;
      const context = canvas.getContext('2d');
      
      if (context && video.readyState === 4 && video.videoWidth > 0) {
          canvas.width = video.videoWidth;
          canvas.height = video.videoHeight;
          context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
          const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
          onCapture(dataUrl);
      }
    };

    const intervalId = setInterval(captureFrame, SCAN_INTERVAL_MS);

    // Cleanup function to clear the interval
    return () => clearInterval(intervalId);

  }, [isProcessing, hasCamera, onCapture, isSystemActive]);

  return (
    <div className="bg-white rounded-lg shadow-lg p-6 h-full flex flex-col justify-between items-center">
      <div className="w-full aspect-video bg-black rounded-md overflow-hidden relative flex justify-center items-center">
        {hasCamera ? (
          <>
            <video ref={videoRef} autoPlay playsInline className={`w-full h-full object-cover transition-all duration-500 ${!isSystemActive ? 'filter grayscale blur-sm' : ''}`} />
            <canvas ref={canvasRef} className="hidden" />
            
            {!isSystemActive && (
                <div className="absolute inset-0 bg-slate-900 bg-opacity-70 flex flex-col justify-center items-center text-center p-4">
                   <PowerIcon className="w-16 h-16 text-slate-500 mb-4" />
                   <h3 className="text-2xl font-bold text-white">Sistema Apagado</h3>
                   <p className="text-slate-300 mt-2">Presione "Encender Sistema" para comenzar a escanear.</p>
                </div>
            )}

            {isProcessing && isSystemActive && (
              <div className="absolute inset-0 bg-black bg-opacity-60 flex flex-col justify-center items-center">
                <LoadingSpinner />
                <p className="text-blue-400 mt-4">Validando Artículo...</p>
              </div>
            )}
          </>
        ) : (
          <div className="text-center text-slate-500 p-4 leading-relaxed">
            <p className="font-bold text-lg">Cámara no disponible</p>
            <p className="mt-2">Para usar la cámara, asegúrese de estar en una conexión segura (HTTPS) y de haber otorgado los permisos necesarios en su navegador.</p>
            <p className="text-xs mt-2 text-slate-400">(El acceso a la cámara a través de HTTP solo funciona en 'localhost'.)</p>
          </div>
        )}
      </div>
      <div className="mt-6 text-center w-full px-4 py-3 bg-slate-100 rounded-lg">
        {isSystemActive ? (
            <>
                <h3 className="font-semibold text-blue-800">Escaneo Automático Activo</h3>
                <p className="text-slate-600 text-sm">Mantenga el artículo quieto en el encuadre.</p>
            </>
        ) : (
            <>
                <h3 className="font-semibold text-red-600">Sistema en Pausa</h3>
                <p className="text-slate-600 text-sm">El escaneo está actualmente inactivo.</p>
            </>
        )}
      </div>
    </div>
  );
};

export default CameraFeed;
