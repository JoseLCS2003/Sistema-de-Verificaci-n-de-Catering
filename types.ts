export interface Product {
  id: string;
  name: string;
  category_id: number;
}

export interface MenuItem {
  id: number;
  menu_id: number;
  product_id: string;
  quantity: number;
  product: Product;
}

export interface Menu {
  id: number;
  name: string;
  menu_items: MenuItem[];
}

export interface Flight {
  id: string;
  flightNumber: string;
  origin: string;
  destination: string;
  menu: Menu;
}

export interface ScannedItems {
  [productId: string]: number;
}

export enum AlertType {
    NONE = 'none',
    SUCCESS = 'success',
    ERROR = 'error',
    INFO = 'info',
}

export interface AlertInfo {
    type: AlertType;
    message: string;
}

export interface GeminiValidationResult {
  isCorrectItem: boolean;
  productId: string | null;
  productName: string | null;
  reason: string;
  liquidPercentage: number | null;
}