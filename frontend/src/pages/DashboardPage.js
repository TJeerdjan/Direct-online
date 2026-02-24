import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Briefcase, MessageSquareQuote, FileText, Inbox, Plus, ArrowRight } from 'lucide-react';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const DashboardPage = () => {
  const { user, isAdmin } = useAuth();
  const { t } = useI18n();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchStats = async () => {
      if (isAdmin) {
        setLoading(false);
        return;
      }
      
      try {
        const response = await axios.get(`${API}/dashboard/stats`);
        setStats(response.data);
      } catch (error) {
        console.error('Failed to fetch stats:', error);
      }
      setLoading(false);
    };
    fetchStats();
  }, [isAdmin]);

  const getPlanLabel = (plan) => {
    const labels = {
      'early-bird': t('plan_early_bird'),
      'starter': t('plan_starter'),
      'growth': t('plan_growth'),
      'webshop': t('plan_webshop')
    };
    return labels[plan] || plan;
  };

  const getPlanClass = (plan) => {
    const classes = {
      'early-bird': 'plan-early-bird',
      'starter': 'plan-starter',
      'growth': 'plan-growth',
      'webshop': 'plan-webshop'
    };
    return classes[plan] || 'plan-starter';
  };

  if (isAdmin) {
    return (
      <div className="animate-fadeIn">
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-white mb-2">
            {t('dashboard_welcome')}, {user?.name}
          </h1>
          <p className="text-white/60">Direct-Online Admin Dashboard</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <Link to="/admin/clients">
            <Card className="dashboard-card cursor-pointer group">
              <CardContent className="p-6 flex items-center gap-4">
                <div className="w-12 h-12 rounded-lg bg-[#129387]/20 flex items-center justify-center group-hover:bg-[#129387]/30 transition-colors">
                  <Briefcase className="w-6 h-6 text-[#129387]" />
                </div>
                <div className="flex-1">
                  <h3 className="text-lg font-semibold text-white">{t('nav_clients')}</h3>
                  <p className="text-white/60 text-sm">{t('admin_clients_subtitle')}</p>
                </div>
                <ArrowRight className="w-5 h-5 text-white/40 group-hover:text-[#129387] transition-colors" />
              </CardContent>
            </Card>
          </Link>

          <Link to="/admin/feedback">
            <Card className="dashboard-card cursor-pointer group">
              <CardContent className="p-6 flex items-center gap-4">
                <div className="w-12 h-12 rounded-lg bg-[#f59d0e]/20 flex items-center justify-center group-hover:bg-[#f59d0e]/30 transition-colors">
                  <MessageSquareQuote className="w-6 h-6 text-[#f59d0e]" />
                </div>
                <div className="flex-1">
                  <h3 className="text-lg font-semibold text-white">{t('admin_feedback_title')}</h3>
                  <p className="text-white/60 text-sm">{t('feedback_subtitle')}</p>
                </div>
                <ArrowRight className="w-5 h-5 text-white/40 group-hover:text-[#f59d0e] transition-colors" />
              </CardContent>
            </Card>
          </Link>
        </div>
      </div>
    );
  }

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
      <div className="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-white mb-2">
            {t('dashboard_welcome')}, {user?.name}
          </h1>
          <p className="text-white/60">{t('dashboard_overview')}</p>
        </div>
        {stats?.plan && (
          <div className={`px-4 py-2 rounded-full text-sm font-medium ${getPlanClass(stats.plan)}`}>
            {getPlanLabel(stats.plan)}
          </div>
        )}
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <Card className="stats-card" data-testid="stat-portfolio">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-white/60 text-sm">{t('dashboard_portfolio_count')}</p>
                <p className="text-3xl font-bold text-white mt-1">{stats?.portfolio_count || 0}</p>
              </div>
              <div className="w-12 h-12 rounded-lg bg-[#129387]/20 flex items-center justify-center">
                <Briefcase className="w-6 h-6 text-[#129387]" />
              </div>
            </div>
          </CardContent>
        </Card>

        <Card className="stats-card" data-testid="stat-testimonials">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-white/60 text-sm">{t('dashboard_testimonials_count')}</p>
                <p className="text-3xl font-bold text-white mt-1">{stats?.testimonials_count || 0}</p>
              </div>
              <div className="w-12 h-12 rounded-lg bg-[#f59d0e]/20 flex items-center justify-center">
                <MessageSquareQuote className="w-6 h-6 text-[#f59d0e]" />
              </div>
            </div>
          </CardContent>
        </Card>

        <Card className="stats-card" data-testid="stat-pages">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-white/60 text-sm">{t('dashboard_pages_count')}</p>
                <p className="text-3xl font-bold text-white mt-1">{stats?.pages_count || 0}</p>
              </div>
              <div className="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center">
                <FileText className="w-6 h-6 text-purple-400" />
              </div>
            </div>
          </CardContent>
        </Card>

        <Card className="stats-card" data-testid="stat-inbox">
          <CardContent className="p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-white/60 text-sm">{t('dashboard_unread_messages')}</p>
                <p className="text-3xl font-bold text-white mt-1">{stats?.unread_messages || 0}</p>
              </div>
              <div className="w-12 h-12 rounded-lg bg-blue-500/20 flex items-center justify-center relative">
                <Inbox className="w-6 h-6 text-blue-400" />
                {stats?.unread_messages > 0 && (
                  <span className="notification-badge">{stats.unread_messages}</span>
                )}
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Quick Actions */}
      <Card className="dashboard-card">
        <CardHeader>
          <CardTitle className="text-white">{t('dashboard_quick_actions')}</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <Link to="/portfolio">
              <Button className="w-full btn-primary justify-start gap-2" data-testid="quick-add-portfolio">
                <Plus className="w-4 h-4" />
                {t('dashboard_add_portfolio')}
              </Button>
            </Link>
            <Link to="/testimonials">
              <Button className="w-full btn-orange justify-start gap-2" data-testid="quick-add-testimonial">
                <Plus className="w-4 h-4" />
                {t('dashboard_add_testimonial')}
              </Button>
            </Link>
            <Link to="/inbox">
              <Button variant="outline" className="w-full justify-start gap-2 border-white/10 text-white hover:bg-white/5" data-testid="quick-view-inbox">
                <Inbox className="w-4 h-4" />
                {t('dashboard_view_inbox')}
              </Button>
            </Link>
          </div>
        </CardContent>
      </Card>
    </div>
  );
};

export default DashboardPage;
