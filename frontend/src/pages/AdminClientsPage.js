import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogFooter } from '../components/ui/dialog';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../components/ui/select';
import { Plus, Pencil, Users, Building2, ExternalLink, UserPlus, Eye } from 'lucide-react';
import { useToast } from '../hooks/use-toast';
import { format } from 'date-fns';
import { nl, enUS } from 'date-fns/locale';
import { useAuth } from '../context/AuthContext';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const AdminClientsPage = () => {
  const { t } = useI18n();
  const { language } = useAuth();
  const { toast } = useToast();
  const [tenants, setTenants] = useState([]);
  const [loading, setLoading] = useState(true);
  const [dialogOpen, setDialogOpen] = useState(false);
  const [userDialogOpen, setUserDialogOpen] = useState(false);
  const [selectedTenant, setSelectedTenant] = useState(null);
  const [viewingDashboard, setViewingDashboard] = useState(null);
  const [formData, setFormData] = useState({
    name: '',
    slug: '',
    domain: '',
    plan: 'early-bird',
    contact_name: '',
    contact_email: ''
  });
  const [userFormData, setUserFormData] = useState({
    name: '',
    email: '',
    password: ''
  });

  const fetchTenants = async () => {
    try {
      const response = await axios.get(`${API}/admin/tenants`);
      setTenants(response.data);
    } catch (error) {
      console.error('Failed to fetch tenants:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchTenants();
  }, []);

  const handleOpenDialog = (tenant = null) => {
    if (tenant) {
      setSelectedTenant(tenant);
      setFormData({
        name: tenant.name,
        slug: tenant.slug,
        domain: tenant.domain || '',
        plan: tenant.plan,
        contact_name: tenant.contact_name,
        contact_email: tenant.contact_email
      });
    } else {
      setSelectedTenant(null);
      setFormData({
        name: '',
        slug: '',
        domain: '',
        plan: 'early-bird',
        contact_name: '',
        contact_email: ''
      });
    }
    setDialogOpen(true);
  };

  const handleOpenUserDialog = (tenant) => {
    setSelectedTenant(tenant);
    setUserFormData({ name: '', email: '', password: '' });
    setUserDialogOpen(true);
  };

  const handleSubmit = async () => {
    try {
      if (selectedTenant) {
        await axios.put(`${API}/admin/tenants/${selectedTenant.id}`, formData);
        toast({ title: 'Klant bijgewerkt' });
      } else {
        await axios.post(`${API}/admin/tenants`, formData);
        toast({ title: 'Klant toegevoegd' });
      }
      setDialogOpen(false);
      fetchTenants();
    } catch (error) {
      console.error('Failed to save:', error);
      toast({ title: 'Error', description: error.response?.data?.detail || 'Er ging iets mis', variant: 'destructive' });
    }
  };

  const handleCreateUser = async () => {
    try {
      await axios.post(`${API}/admin/tenants/${selectedTenant.id}/user`, userFormData);
      toast({ title: 'Gebruiker aangemaakt' });
      setUserDialogOpen(false);
    } catch (error) {
      console.error('Failed to create user:', error);
      toast({ title: 'Error', description: error.response?.data?.detail || 'Er ging iets mis', variant: 'destructive' });
    }
  };

  const handleViewDashboard = async (tenant) => {
    setViewingDashboard(tenant.id);
    try {
      const response = await axios.post(`${API}/admin/tenants/${tenant.id}/impersonate`);
      const { access_token, tenant_name } = response.data;
      
      // Open dashboard in new tab with impersonation token
      const dashboardUrl = `${window.location.origin}/dashboard?impersonate=${access_token}`;
      
      // Store token and open new tab
      const newTab = window.open('about:blank', '_blank');
      if (newTab) {
        // Write a small HTML page that sets the token and redirects
        newTab.document.write(`
          <!DOCTYPE html>
          <html>
            <head>
              <title>Opening ${tenant_name} Dashboard...</title>
              <style>
                body { 
                  background: #0e172c; 
                  color: white; 
                  font-family: system-ui; 
                  display: flex; 
                  align-items: center; 
                  justify-content: center; 
                  height: 100vh; 
                  margin: 0;
                }
                .loader { text-align: center; }
                .spinner {
                  width: 40px;
                  height: 40px;
                  border: 3px solid #334157;
                  border-top-color: #129387;
                  border-radius: 50%;
                  animation: spin 1s linear infinite;
                  margin: 0 auto 1rem;
                }
                @keyframes spin { to { transform: rotate(360deg); } }
              </style>
            </head>
            <body>
              <div class="loader">
                <div class="spinner"></div>
                <p>Opening dashboard voor ${tenant_name}...</p>
              </div>
              <script>
                localStorage.setItem('token', '${access_token}');
                window.location.href = '${window.location.origin}/dashboard';
              </script>
            </body>
          </html>
        `);
        newTab.document.close();
      }
      
      toast({ title: `Dashboard ${tenant_name} geopend in nieuw tabblad` });
    } catch (error) {
      console.error('Failed to impersonate:', error);
      toast({ title: 'Error', description: 'Kon dashboard niet openen', variant: 'destructive' });
    }
    setViewingDashboard(null);
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return format(date, 'dd MMM yyyy', { locale: language === 'nl' ? nl : enUS });
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

  const getStatusColor = (status) => {
    switch (status) {
      case 'active': return 'active';
      case 'suspended': return 'suspended';
      case 'cancelled': return 'cancelled';
      default: return 'active';
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
      <div className="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-white mb-2">{t('admin_clients_title')}</h1>
          <p className="text-white/60">{t('admin_clients_subtitle')}</p>
        </div>
        <Button onClick={() => handleOpenDialog()} className="btn-primary gap-2" data-testid="add-client-btn">
          <Plus className="w-4 h-4" />
          {t('admin_add_client')}
        </Button>
      </div>

      {/* Content */}
      {tenants.length === 0 ? (
        <Card className="dashboard-card">
          <CardContent className="empty-state">
            <Users className="empty-state-icon" />
            <h3 className="text-xl font-semibold text-white mb-2">Nog geen klanten</h3>
            <p className="text-white/60 mb-4">Voeg je eerste klant toe</p>
            <Button onClick={() => handleOpenDialog()} className="btn-primary gap-2">
              <Plus className="w-4 h-4" />
              {t('admin_add_client')}
            </Button>
          </CardContent>
        </Card>
      ) : (
        <Card className="dashboard-card overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full table-dark">
              <thead>
                <tr>
                  <th className="text-left p-4">{t('admin_client_name')}</th>
                  <th className="text-left p-4">{t('admin_client_contact')}</th>
                  <th className="text-left p-4">{t('admin_client_plan')}</th>
                  <th className="text-left p-4">{t('admin_client_status')}</th>
                  <th className="text-left p-4">Aangemaakt</th>
                  <th className="text-right p-4">{t('actions')}</th>
                </tr>
              </thead>
              <tbody>
                {tenants.map((tenant) => (
                  <tr key={tenant.id} data-testid={`tenant-row-${tenant.id}`}>
                    <td className="p-4">
                      <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-lg bg-[#129387]/20 flex items-center justify-center">
                          <Building2 className="w-5 h-5 text-[#129387]" />
                        </div>
                        <div>
                          <p className="font-medium text-white">{tenant.name}</p>
                          <p className="text-xs text-white/40">{tenant.slug}</p>
                        </div>
                      </div>
                    </td>
                    <td className="p-4">
                      <p className="text-white">{tenant.contact_name}</p>
                      <p className="text-sm text-white/60">{tenant.contact_email}</p>
                    </td>
                    <td className="p-4">
                      <span className={`px-3 py-1 rounded-full text-xs font-medium ${getPlanClass(tenant.plan)}`}>
                        {t(`plan_${tenant.plan.replace('-', '_')}`)}
                      </span>
                    </td>
                    <td className="p-4">
                      <div className="flex items-center gap-2">
                        <span className={`status-dot ${getStatusColor(tenant.status)}`} />
                        <span className="text-white">{t(`status_${tenant.status}`)}</span>
                      </div>
                    </td>
                    <td className="p-4 text-white/60">
                      {formatDate(tenant.created_at)}
                    </td>
                    <td className="p-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <Button 
                          variant="ghost" 
                          size="sm" 
                          onClick={() => handleOpenUserDialog(tenant)}
                          className="text-[#129387] hover:text-[#129387] hover:bg-[#129387]/10"
                          title="Gebruiker toevoegen"
                          data-testid={`add-user-${tenant.id}`}
                        >
                          <UserPlus className="w-4 h-4" />
                        </Button>
                        <Button 
                          variant="ghost" 
                          size="sm" 
                          onClick={() => handleOpenDialog(tenant)}
                          className="text-white/60 hover:text-white hover:bg-white/5"
                          data-testid={`edit-tenant-${tenant.id}`}
                        >
                          <Pencil className="w-4 h-4" />
                        </Button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </Card>
      )}

      {/* Add/Edit Client Dialog */}
      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="bg-[#1c2336] border-white/10 text-white max-w-lg">
          <DialogHeader>
            <DialogTitle>{selectedTenant ? 'Klant bewerken' : t('admin_add_client')}</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('admin_client_name')}</Label>
                <Input
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="input-dark"
                  data-testid="tenant-name-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('admin_client_slug')}</Label>
                <Input
                  value={formData.slug}
                  onChange={(e) => setFormData({ ...formData, slug: e.target.value.toLowerCase().replace(/[^a-z0-9-]/g, '-') })}
                  className="input-dark"
                  placeholder="bedrijfsnaam"
                  disabled={!!selectedTenant}
                  data-testid="tenant-slug-input"
                />
              </div>
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('admin_client_domain')}</Label>
                <Input
                  value={formData.domain}
                  onChange={(e) => setFormData({ ...formData, domain: e.target.value })}
                  className="input-dark"
                  placeholder="website.nl"
                  data-testid="tenant-domain-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('admin_client_plan')}</Label>
                <Select value={formData.plan} onValueChange={(v) => setFormData({ ...formData, plan: v })}>
                  <SelectTrigger className="input-dark" data-testid="tenant-plan-select">
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent className="bg-[#1c2336] border-white/10">
                    <SelectItem value="early-bird">{t('plan_early_bird')}</SelectItem>
                    <SelectItem value="starter">{t('plan_starter')}</SelectItem>
                    <SelectItem value="growth">{t('plan_growth')}</SelectItem>
                    <SelectItem value="webshop">{t('plan_webshop')}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('admin_client_contact')}</Label>
                <Input
                  value={formData.contact_name}
                  onChange={(e) => setFormData({ ...formData, contact_name: e.target.value })}
                  className="input-dark"
                  data-testid="tenant-contact-name-input"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">Contact Email</Label>
                <Input
                  type="email"
                  value={formData.contact_email}
                  onChange={(e) => setFormData({ ...formData, contact_email: e.target.value })}
                  className="input-dark"
                  data-testid="tenant-contact-email-input"
                />
              </div>
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setDialogOpen(false)} className="border-white/10 text-white hover:bg-white/5">
              {t('cancel')}
            </Button>
            <Button onClick={handleSubmit} className="btn-primary" data-testid="tenant-save-btn">
              {t('save')}
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Create User Dialog */}
      <Dialog open={userDialogOpen} onOpenChange={setUserDialogOpen}>
        <DialogContent className="bg-[#1c2336] border-white/10 text-white max-w-md">
          <DialogHeader>
            <DialogTitle>Gebruiker aanmaken voor {selectedTenant?.name}</DialogTitle>
          </DialogHeader>
          <div className="space-y-4 py-4">
            <div className="space-y-2">
              <Label className="text-white/80">Naam</Label>
              <Input
                value={userFormData.name}
                onChange={(e) => setUserFormData({ ...userFormData, name: e.target.value })}
                className="input-dark"
                data-testid="user-name-input"
              />
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">Email</Label>
              <Input
                type="email"
                value={userFormData.email}
                onChange={(e) => setUserFormData({ ...userFormData, email: e.target.value })}
                className="input-dark"
                data-testid="user-email-input"
              />
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">Wachtwoord</Label>
              <Input
                type="password"
                value={userFormData.password}
                onChange={(e) => setUserFormData({ ...userFormData, password: e.target.value })}
                className="input-dark"
                data-testid="user-password-input"
              />
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setUserDialogOpen(false)} className="border-white/10 text-white hover:bg-white/5">
              {t('cancel')}
            </Button>
            <Button onClick={handleCreateUser} className="btn-primary" data-testid="user-save-btn">
              Aanmaken
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
};

export default AdminClientsPage;
