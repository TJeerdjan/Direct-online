import React, { createContext, useContext } from 'react';
import { translations } from '../i18n/translations';
import { useAuth } from './AuthContext';

const I18nContext = createContext(null);

export const I18nProvider = ({ children }) => {
  const { language } = useAuth();

  const t = (key) => {
    return translations[language]?.[key] || translations['nl'][key] || key;
  };

  return (
    <I18nContext.Provider value={{ t, language }}>
      {children}
    </I18nContext.Provider>
  );
};

export const useI18n = () => {
  const context = useContext(I18nContext);
  if (!context) {
    throw new Error('useI18n must be used within I18nProvider');
  }
  return context;
};
