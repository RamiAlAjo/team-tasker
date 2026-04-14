import React, { useState, useEffect, createContext, useContext } from 'react';
import api from './services/api';

// Auth Context
const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(localStorage.getItem('token') || null);

  useEffect(() => {
    if (token) {
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      fetchUser();
    }
  }, [token]);

  const fetchUser = async () => {
    try {
      const response = await api.get('/user');
      setUser(response.data);
    } catch (error) {
      console.error('Failed to fetch user:', error);
    }
  };

  const login = async (email, password) => {
    try {
      const response = await api.post('/login', { email, password });
      setToken(response.token);
      setUser(response.user);
      localStorage.setItem('token', response.token);
      return { success: true, data: response };
    } catch (error) {
      return { success: false, error: error.response?.data?.error || 'Login failed' };
    }
  };

  const register = async (name, email, password) => {
    try {
      const response = await api.post('/register', { name, email, password });
      setToken(response.token);
      setUser(response.user);
      localStorage.setItem('token', response.token);
      return { success: true, data: response };
    } catch (error) {
      return { success: false, error: error.response?.data?.error || 'Registration failed' };
    }
  };

  const logout = async () => {
    try {
      await api.post('/logout');
      setToken(null);
      setUser(null);
      localStorage.removeItem('token');
      return { success: true };
    } catch (error) {
      return { success: false, error: 'Logout failed' };
    }
  };

  return (
    <AuthContext.Provider value={{ user, token, login, register, logout, fetchUser }}>
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