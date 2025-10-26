import { GoogleGenAI, GenerateContentResponse, Type } from "@google/genai";
import { MenuItem, GeminiValidationResult } from '../types';

// This function converts a data URL to a base64 string
const fileToGenerativePart = (dataUrl: string) => {
    const base64Data = dataUrl.split(',')[1];
    const mimeType = dataUrl.split(',')[0].split(':')[1].split(';')[0];
    return {
        inlineData: {
            data: base64Data,
            mimeType,
        },
    };
};

const validationSchema = {
  type: Type.OBJECT,
  properties: {
    isCorrectItem: {
      type: Type.BOOLEAN,
      description: "¿El artículo en la imagen es uno de los productos requeridos en la lista de empaque?",
    },
    productId: {
      type: Type.STRING,
      description: "El 'id' del producto identificado de la lista de empaque. Debe ser nulo si el artículo es incorrecto o no está en la lista.",
    },
    productName: {
      type: Type.STRING,
      description: "El 'name' del producto identificado de la lista de empaque. Debe ser nulo si el artículo es incorrecto o no está en la lista.",
    },
    reason: {
      type: Type.STRING,
      description: "Una explicación muy breve y fácil de entender para el operador. Ej: 'Artículo correcto: Lata de Refresco.', 'Artículo incorrecto. Es una botella de agua.', 'Artículo no reconocido.'",
    },
    liquidPercentage: {
      type: Type.NUMBER,
      description: "Si el artículo identificado es una botella que contiene líquido (como agua), estima el porcentaje de líquido restante (0-100). Para artículos no líquidos o si la estimación no es posible, debe ser nulo.",
    },
  },
  required: ["isCorrectItem", "productId", "productName", "reason"],
};


export const validateProductWithGemini = async (
  imageDataUrl: string,
  menuItems: MenuItem[]
): Promise<GeminiValidationResult> => {
  if (!process.env.API_KEY) {
    throw new Error("API_KEY environment variable is not set.");
  }
  const ai = new GoogleGenAI({ apiKey: process.env.API_KEY });

  const imagePart = fileToGenerativePart(imageDataUrl);

  const packingList = menuItems.map(item => ({
      id: item.product.id,
      name: item.product.name,
      required_quantity: item.quantity
  }));

  const prompt = `Eres un asistente de IA para un servicio de catering de una aerolínea. Tu tarea es identificar el producto en la imagen y verificar si está en la lista de empaque proporcionada.
  
  Lista de Empaque:
  ${JSON.stringify(packingList, null, 2)}

  Analiza la imagen y determina si el producto principal que se muestra está en la lista de empaque.
  **Fundamental: si el artículo es una botella que contiene líquido (p. ej., una botella de agua), DEBES estimar también el porcentaje de líquido que queda dentro.**
  Responde ÚNICAMENTE con el objeto JSON que coincida con el esquema solicitado.`;
  
  try {
    const response: GenerateContentResponse = await ai.models.generateContent({
      // Fix: Use the recommended model for basic text tasks.
      model: 'gemini-2.5-flash',
      contents: { parts: [imagePart, { text: prompt }] },
      config: {
        responseMimeType: "application/json",
        responseSchema: validationSchema,
      },
    });

    // Fix: Access the text directly from the response object and parse it.
    const resultText = response.text;
    const resultJson = JSON.parse(resultText);
    return resultJson as GeminiValidationResult;

  } catch (error) {
    console.error("Error calling Gemini API:", error);
    return {
      isCorrectItem: false,
      productId: null,
      productName: null,
      reason: "Ocurrió un error durante la validación de la IA. Por favor, inténtelo de nuevo.",
      liquidPercentage: null,
    };
  }
};