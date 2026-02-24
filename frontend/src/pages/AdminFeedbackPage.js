import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { MessageCircle, Bug, Lightbulb, Building2 } from 'lucide-react';
import { format } from 'date-fns';
import { nl, enUS } from 'date-fns/locale';
import { useAuth } from '../context/AuthContext';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const AdminFeedbackPage = () => {
  const { t } = useI18n();
  const { language } = useAuth();
  const [feedbackList, setFeedbackList] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchFeedback = async () => {
    try {
      const response = await axios.get(`${API}/admin/feedback`);
      setFeedbackList(response.data);
    } catch (error) {
      console.error('Failed to fetch feedback:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchFeedback();
  }, []);

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return format(date, 'dd MMM yyyy, HH:mm', { locale: language === 'nl' ? nl : enUS });
  };

  const getTypeIcon = (type) => {
    switch (type) {
      case 'bug': return <Bug className="w-4 h-4 text-red-400" />;
      case 'feature': return <Lightbulb className="w-4 h-4 text-yellow-400" />;
      default: return <MessageCircle className="w-4 h-4 text-blue-400" />;
    }
  };

  const getTypeLabel = (type) => {
    switch (type) {
      case 'bug': return t('feedback_type_bug');
      case 'feature': return t('feedback_type_feature');
      default: return t('feedback_type_general');
    }
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
        <h1 className="text-3xl font-bold text-white mb-2">{t('admin_feedback_title')}</h1>
        <p className="text-white/60">Alle feedback van klanten</p>
      </div>

      {/* Content */}
      {feedbackList.length === 0 ? (
        <Card className="dashboard-card">
          <CardContent className="empty-state">
            <MessageCircle className="empty-state-icon" />
            <h3 className="text-xl font-semibold text-white mb-2">Geen feedback</h3>
            <p className="text-white/60">Er is nog geen feedback van klanten</p>
          </CardContent>
        </Card>
      ) : (
        <div className="space-y-4">
          {feedbackList.map((item) => (
            <Card key={item.id} className="dashboard-card" data-testid={`admin-feedback-${item.id}`}>
              <CardContent className="p-6">
                <div className="flex items-start justify-between mb-4">
                  <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-[#129387]/20 flex items-center justify-center">
                      <Building2 className="w-5 h-5 text-[#129387]" />
                    </div>
                    <div>
                      <p className="font-medium text-white">{item.tenant_name}</p>
                      <p className="text-sm text-white/60">{item.user_name}</p>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="flex items-center gap-2 text-white/60">
                      {getTypeIcon(item.type)}
                      <span className="text-sm">{getTypeLabel(item.type)}</span>
                    </div>
                    <span className={`px-2 py-1 rounded text-xs font-medium ${item.status === 'new' ? 'badge-orange' : 'badge-teal'}`}>
                      {item.status}
                    </span>
                  </div>
                </div>
                
                <div className="bg-[#0e172c] rounded-lg p-4 mb-3">
                  <p className="text-white">{item.message}</p>
                </div>
                
                <div className="flex items-center justify-between text-xs text-white/40">
                  <span>{item.page && `Pagina: ${item.page}`}</span>
                  <span>{formatDate(item.created_at)}</span>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      )}
    </div>
  );
};

export default AdminFeedbackPage;
