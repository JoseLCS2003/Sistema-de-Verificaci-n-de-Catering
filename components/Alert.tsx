import React from 'react';
import { AlertInfo, AlertType } from '../types';
import { CheckCircleIcon, XCircleIcon } from './icons';

interface AlertProps {
  alertInfo: AlertInfo;
  onClose: () => void;
}

const Alert: React.FC<AlertProps> = ({ alertInfo, onClose }) => {
  if (alertInfo.type === AlertType.NONE) {
    return null;
  }

  const baseClasses = 'fixed bottom-5 right-5 flex items-center p-4 mb-4 text-sm rounded-lg shadow-lg z-50';
  const typeClasses = {
    [AlertType.SUCCESS]: 'text-green-800 bg-green-100',
    [AlertType.ERROR]: 'text-red-800 bg-red-100',
    [AlertType.INFO]: 'text-blue-800 bg-blue-100',
    [AlertType.NONE]: '',
  };
  
  const Icon = alertInfo.type === AlertType.SUCCESS ? CheckCircleIcon : XCircleIcon;

  return (
    <div className={`${baseClasses} ${typeClasses[alertInfo.type]}`} role="alert">
      <Icon className="w-6 h-6 mr-3" />
      <span className="font-medium">{alertInfo.message}</span>
      <button
        type="button"
        className="ml-auto -mx-1.5 -my-1.5 bg-transparent text-current rounded-lg focus:ring-2 p-1.5 inline-flex items-center justify-center h-8 w-8"
        onClick={onClose}
        aria-label="Cerrar"
      >
        <span className="sr-only">Cerrar</span>
        <svg className="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
      </button>
    </div>
  );
};

export default Alert;