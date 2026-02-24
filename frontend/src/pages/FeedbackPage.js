import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Label } from '../components/ui/label';
import { Textarea } from '../components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { MessageCircle, Bug, Lightbulb, Send, Clock } from 'lucide-react';
import { useToast } from '../components/ui/use-toast';
import { format } from 'date-fns';
import { nl, enUS } from 'date-fns/locale';
import { useAuth } from '../context/AuthContext';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const FeedbackPage = () => {
  const { t } = useI18n();
  const { language } = useAuth();
  const { toast } = useToast();
  const [feedbackList, setFeedbackList] = useState([]);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [formData, setFormData] = useState({
    type: 'general',
    message: '',
    page: ''
  });

  const fetchFeedback = async () => {
    try {
      const response = await axios.get(`${API}/feedback`);
      setFeedbackList(response.data);
    } catch (error) {
      console.error('Failed to fetch feedback:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchFeedback();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.message.trim()) return;

    setSubmitting(true);
    try {
      await axios.post(`${API}/feedback`, {
        ...formData,
        page: window.location.pathname
      });
      toast({ title: t('feedback_success') });
      setFormData({ type: 'general', message: '', page: '' });
      fetchFeedback();
    } catch (error) {
      console.error('Failed to submit feedback:', error);
      toast({ title: 'Error', variant: 'destructive' });
    }
    setSubmitting(false);
  };

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

  return (
    <div className="animate-fadeIn">
      {/* Header */}
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-white mb-2">{t('feedback_title')}</h1>
        <p className="text-white/60">{t('feedback_subtitle')}</p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Submit Feedback */}
        <Card className="dashboard-card">
          <CardHeader>
            <CardTitle className="text-white flex items-center gap-2">
              <Send className="w-5 h-5 text-[#129387]" />
              {t('feedback_send')}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('feedback_type')}</Label>
                <Select value={formData.type} onValueChange={(v) => setFormData({ ...formData, type: v })}>
                  <SelectTrigger className="input-dark" data-testid="feedback-type-select">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent className="bg-[#1c2336] border-white/10">
                    <SelectItem value="general">
                      <span className="flex items-center gap-2">
                        <MessageCircle className="w-4 h-4" />
                        {t('feedback_type_general')}
                      </span>
                    </SelectItem>
                    <SelectItem value="bug">
                      <span className="flex items-center gap-2">
                        <Bug className="w-4 h-4" />
                        {t('feedback_type_bug')}
                      </span>
                    </SelectItem>
                    <SelectItem value="feature">
                      <span className="flex items-center gap-2">
                        <Lightbulb className="w-4 h-4" />
                        {t('feedback_type_feature')}
                      </span>
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div className="space-y-2">
                <Label className="text-white/80">{t('feedback_message')}</Label>
                <Textarea
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  className="input-dark min-h-32"
                  placeholder="Vertel ons wat je denkt..."
                  required
                  data-testid="feedback-message-input"
                />
              </div>

              <Button 
                type="submit" 
                className="w-full btn-primary gap-2" 
                disabled={submitting || !formData.message.trim()}
                data-testid="feedback-submit-btn"
              >
                <Send className="w-4 h-4" />
                {submitting ? t('loading') : t('feedback_send')}
              </Button>
            </form>
          </CardContent>
        </Card>

        {/* Feedback History */}
        <Card className="dashboard-card">
          <CardHeader>
            <CardTitle className="text-white flex items-center gap-2">
              <Clock className="w-5 h-5 text-[#f59d0e]" />
              {t('feedback_history')}
            </CardTitle>
          </CardHeader>
          <CardContent>
            {loading ? (
              <div className="flex items-center justify-center py-8">
                <div className="animate-spin rounded-full h-6 w-6 border-b-2 border-[#129387]"></div>
              </div>
            ) : feedbackList.length === 0 ? (
              <div className="text-center py-8 text-white/40">
                <MessageCircle className="w-12 h-12 mx-auto mb-4 opacity-30" />
                <p>{t('feedback_empty')}</p>
              </div>
            ) : (
              <div className="space-y-4 max-h-96 overflow-y-auto pr-2">
                {feedbackList.map((item) => (
                  <div key={item.id} className="p-4 bg-[#0e172c] rounded-lg" data-testid={`feedback-item-${item.id}`}>
                    <div className="flex items-center justify-between mb-2">
                      <div className="flex items-center gap-2">
                        {getTypeIcon(item.type)}
                        <span className="text-sm text-white/60">{getTypeLabel(item.type)}</span>
                      </div>
                      <span className={`px-2 py-1 rounded text-xs font-medium ${item.status === 'new' ? 'badge-orange' : 'badge-teal'}`}>
                        {item.status}
                      </span>
                    </div>
                    <p className="text-white mb-2">{item.message}</p>
                    <p className="text-xs text-white/30">{formatDate(item.created_at)}</p>
                  </div>
                ))}
              </div>
            )}
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

export default FeedbackPage;
