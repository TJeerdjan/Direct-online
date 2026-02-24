import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Inbox, Mail, Archive, CheckCheck } from 'lucide-react';
import { useToast } from '../hooks/use-toast';
import { format } from 'date-fns';
import { nl, enUS } from 'date-fns/locale';
import { useAuth } from '../context/AuthContext';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const InboxPage = () => {
  const { t } = useI18n();
  const { language } = useAuth();
  const { toast } = useToast();
  const [messages, setMessages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedMessage, setSelectedMessage] = useState(null);

  const fetchMessages = async () => {
    try {
      const response = await axios.get(`${API}/inbox`);
      setMessages(response.data);
    } catch (error) {
      console.error('Failed to fetch inbox:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchMessages();
  }, []);

  const handleMarkAsRead = async (id) => {
    try {
      await axios.put(`${API}/inbox/${id}/read`);
      setMessages(messages.map(m => m.id === id ? { ...m, read: true } : m));
      toast({ description: t('inbox_mark_read') });
    } catch (error) {
      console.error('Failed to mark as read:', error);
    }
  };

  const handleArchive = async (id) => {
    try {
      await axios.put(`${API}/inbox/${id}/archive`);
      setMessages(messages.filter(m => m.id !== id));
      setSelectedMessage(null);
      toast({ description: t('inbox_archive') });
    } catch (error) {
      console.error('Failed to archive:', error);
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return format(date, 'dd MMM yyyy, HH:mm', { locale: language === 'nl' ? nl : enUS });
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-[#129387]"></div>
      </div>
    );
  }

  return (
    <div className="animate-fadeIn">
      {/* Header */}
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-white mb-2">{t('inbox_title')}</h1>
        <p className="text-white/60">{t('inbox_subtitle')}</p>
      </div>

      {/* Content */}
      {messages.length === 0 ? (
        <Card className="dashboard-card">
          <CardContent className="empty-state">
            <Inbox className="empty-state-icon" />
            <h3 className="text-xl font-semibold text-white mb-2">{t('inbox_empty')}</h3>
            <p className="text-white/60">{t('inbox_empty_desc')}</p>
          </CardContent>
        </Card>
      ) : (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Message List */}
          <div className="lg:col-span-1 space-y-2">
            {messages.map((message) => (
              <Card 
                key={message.id} 
                className={`dashboard-card cursor-pointer transition-all ${selectedMessage?.id === message.id ? 'border-[#129387]' : ''} ${!message.read ? 'border-l-4 border-l-[#129387]' : ''}`}
                onClick={() => {
                  setSelectedMessage(message);
                  if (!message.read) handleMarkAsRead(message.id);
                }}
                data-testid={`inbox-item-${message.id}`}
              >
                <CardContent className="p-4">
                  <div className="flex items-start justify-between mb-2">
                    <div className="flex items-center gap-2">
                      {!message.read && (
                        <span className="w-2 h-2 rounded-full bg-[#129387]" />
                      )}
                      <span className="font-medium text-white">{message.name}</span>
                    </div>
                  </div>
                  <p className="text-sm text-white/60 truncate mb-1">{message.email}</p>
                  <p className="text-sm text-white/40 line-clamp-2">{message.message}</p>
                  <p className="text-xs text-white/30 mt-2">{formatDate(message.created_at)}</p>
                </CardContent>
              </Card>
            ))}
          </div>

          {/* Message Detail */}
          <div className="lg:col-span-2">
            {selectedMessage ? (
              <Card className="dashboard-card h-full">
                <CardContent className="p-6">
                  <div className="flex items-start justify-between mb-6">
                    <div>
                      <h2 className="text-xl font-semibold text-white mb-1">{selectedMessage.name}</h2>
                      <a href={`mailto:${selectedMessage.email}`} className="text-[#129387] hover:underline flex items-center gap-1">
                        <Mail className="w-4 h-4" />
                        {selectedMessage.email}
                      </a>
                    </div>
                    <div className="flex items-center gap-2">
                      {!selectedMessage.read && (
                        <Button 
                          variant="outline" 
                          size="sm" 
                          onClick={() => handleMarkAsRead(selectedMessage.id)}
                          className="border-white/10 text-white hover:bg-white/5"
                          data-testid="mark-read-btn"
                        >
                          <CheckCheck className="w-4 h-4 mr-1" />
                          {t('inbox_mark_read')}
                        </Button>
                      )}
                      <Button 
                        variant="outline" 
                        size="sm" 
                        onClick={() => handleArchive(selectedMessage.id)}
                        className="border-white/10 text-white hover:bg-white/5"
                        data-testid="archive-btn"
                      >
                        <Archive className="w-4 h-4 mr-1" />
                        {t('inbox_archive')}
                      </Button>
                    </div>
                  </div>
                  
                  <div className="text-white/40 text-sm mb-4">
                    {formatDate(selectedMessage.created_at)} • {selectedMessage.source_page}
                  </div>
                  
                  <div className="bg-[#0e172c] rounded-lg p-4">
                    <p className="text-white whitespace-pre-wrap">{selectedMessage.message}</p>
                  </div>
                </CardContent>
              </Card>
            ) : (
              <Card className="dashboard-card h-full">
                <CardContent className="flex items-center justify-center h-64 text-white/40">
                  <div className="text-center">
                    <Mail className="w-12 h-12 mx-auto mb-4 opacity-30" />
                    <p>Selecteer een bericht om te lezen</p>
                  </div>
                </CardContent>
              </Card>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default InboxPage;
