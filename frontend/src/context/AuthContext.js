import React, { createContext, useContext, useState, useEffect } from 'react';
import axios from 'axios';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token'));
  const [loading, setLoading] = useState(true);
  const [language, setLanguageState] = useState(localStorage.getItem('language') || 'nl');

  // Set up axios interceptor for auth
  useEffect(() => {
    if (token) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    } else {
      delete axios.defaults.headers.common['Authorization'];
    }
  }, [token]);

  // Check if user is logged in on mount
  useEffect(() => {
    const checkAuth = async () => {
      if (token) {
        try {
          const response = await axios.get(`${API}/auth/me`);
          setUser(response.data);
          setLanguageState(response.data.language || 'nl');
        } catch (error) {
          console.error('Auth check failed:', error);
          logout();
        }
      }
      setLoading(false);
    };
    checkAuth();
  }, [token]);

  const login = async (email, password) => {
    try {
      const response = await axios.post(`${API}/auth/login`, { email, password });
      const { access_token, user: userData } = response.data;
      
      localStorage.setItem('token', access_token);
      setToken(access_token);
      setUser(userData);
      setLanguageState(userData.language || 'nl');
      
      return { success: true };
    } catch (error) {
      console.error('Login failed:', error);
      return { success: false, error: error.response?.data?.detail || 'Login failed' };
    }
  };

  const logout = () => {
    localStorage.removeItem('token');
    setToken(null);
    setUser(null);
  };

  const setLanguage = async (lang) => {
    setLanguageState(lang);
    localStorage.setItem('language', lang);
    
    if (token) {
      try {
        await axios.put(`${API}/auth/language?language=${lang}`);
      } catch (error) {
        console.error('Failed to update language:', error);
      }
    }
  };


  const isModuleEnabled = (modulePath) => {
    if (!modulePath || user?.role === 'agency_admin') {
      return true;
    }

    const [moduleKey, property = 'enabled'] = modulePath.split('.');
    return user?.modules?.[moduleKey]?.[property] !== false;
  };

  return (
    <AuthContext.Provider value={{
      user,
      token,
      loading,
      language,
      login,
      logout,
      setLanguage,
      isAuthenticated: !!token && !!user,
      isAdmin: user?.role === 'agency_admin',
      isModuleEnabled
    }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
};
