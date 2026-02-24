import React, { useEffect, useState } from 'react';
import { useI18n } from '../context/I18nContext';
import axios from 'axios';
import { Card, CardContent, CardHeader, CardTitle } from '../components/ui/card';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Settings, Save, Palette, Share2 } from 'lucide-react';
import { useToast } from '../components/ui/use-toast';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

const SettingsPage = () => {
  const { t } = useI18n();
  const { toast } = useToast();
  const [settings, setSettings] = useState(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [formData, setFormData] = useState({
    site_name: '',
    tagline: '',
    logo: '',
    colors: { primary: '#129387', accent: '#f59d0e' },
    social: { instagram: '', linkedin: '', facebook: '', twitter: '' }
  });

  const fetchSettings = async () => {
    try {
      const response = await axios.get(`${API}/settings`);
      setSettings(response.data);
      setFormData({
        site_name: response.data.site_name || '',
        tagline: response.data.tagline || '',
        logo: response.data.logo || '',
        colors: response.data.colors || { primary: '#129387', accent: '#f59d0e' },
        social: response.data.social || { instagram: '', linkedin: '', facebook: '', twitter: '' }
      });
    } catch (error) {
      console.error('Failed to fetch settings:', error);
    }
    setLoading(false);
  };

  useEffect(() => {
    fetchSettings();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setSaving(true);
    try {
      await axios.put(`${API}/settings`, formData);
      toast({ title: t('settings_saved') });
    } catch (error) {
      console.error('Failed to save settings:', error);
      toast({ title: 'Error', variant: 'destructive' });
    }
    setSaving(false);
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
        <h1 className="text-3xl font-bold text-white mb-2">{t('settings_title')}</h1>
        <p className="text-white/60">{t('settings_subtitle')}</p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-6">
        {/* General Settings */}
        <Card className="dashboard-card">
          <CardHeader>
            <CardTitle className="text-white flex items-center gap-2">
              <Settings className="w-5 h-5 text-[#129387]" />
              Algemeen
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_site_name')}</Label>
                <Input
                  value={formData.site_name}
                  onChange={(e) => setFormData({ ...formData, site_name: e.target.value })}
                  className="input-dark"
                  data-testid="settings-site-name"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_tagline')}</Label>
                <Input
                  value={formData.tagline}
                  onChange={(e) => setFormData({ ...formData, tagline: e.target.value })}
                  className="input-dark"
                  data-testid="settings-tagline"
                />
              </div>
            </div>
            <div className="space-y-2">
              <Label className="text-white/80">{t('settings_logo')}</Label>
              <Input
                value={formData.logo}
                onChange={(e) => setFormData({ ...formData, logo: e.target.value })}
                className="input-dark"
                placeholder="https://example.com/logo.png"
                data-testid="settings-logo"
              />
              {formData.logo && (
                <div className="mt-2 p-4 bg-[#0e172c] rounded-lg inline-block">
                  <img src={formData.logo} alt="Logo preview" className="h-12 object-contain" />
                </div>
              )}
            </div>
          </CardContent>
        </Card>

        {/* Colors */}
        <Card className="dashboard-card">
          <CardHeader>
            <CardTitle className="text-white flex items-center gap-2">
              <Palette className="w-5 h-5 text-[#f59d0e]" />
              {t('settings_colors')}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_primary_color')}</Label>
                <div className="flex gap-2">
                  <Input
                    type="color"
                    value={formData.colors.primary}
                    onChange={(e) => setFormData({ ...formData, colors: { ...formData.colors, primary: e.target.value } })}
                    className="w-12 h-10 p-1 rounded bg-transparent border border-white/10"
                    data-testid="settings-primary-color"
                  />
                  <Input
                    value={formData.colors.primary}
                    onChange={(e) => setFormData({ ...formData, colors: { ...formData.colors, primary: e.target.value } })}
                    className="input-dark flex-1"
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_accent_color')}</Label>
                <div className="flex gap-2">
                  <Input
                    type="color"
                    value={formData.colors.accent}
                    onChange={(e) => setFormData({ ...formData, colors: { ...formData.colors, accent: e.target.value } })}
                    className="w-12 h-10 p-1 rounded bg-transparent border border-white/10"
                    data-testid="settings-accent-color"
                  />
                  <Input
                    value={formData.colors.accent}
                    onChange={(e) => setFormData({ ...formData, colors: { ...formData.colors, accent: e.target.value } })}
                    className="input-dark flex-1"
                  />
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Social Media */}
        <Card className="dashboard-card">
          <CardHeader>
            <CardTitle className="text-white flex items-center gap-2">
              <Share2 className="w-5 h-5 text-purple-400" />
              {t('settings_social')}
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_instagram')}</Label>
                <Input
                  value={formData.social.instagram || ''}
                  onChange={(e) => setFormData({ ...formData, social: { ...formData.social, instagram: e.target.value } })}
                  className="input-dark"
                  placeholder="https://instagram.com/username"
                  data-testid="settings-instagram"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_linkedin')}</Label>
                <Input
                  value={formData.social.linkedin || ''}
                  onChange={(e) => setFormData({ ...formData, social: { ...formData.social, linkedin: e.target.value } })}
                  className="input-dark"
                  placeholder="https://linkedin.com/in/username"
                  data-testid="settings-linkedin"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_facebook')}</Label>
                <Input
                  value={formData.social.facebook || ''}
                  onChange={(e) => setFormData({ ...formData, social: { ...formData.social, facebook: e.target.value } })}
                  className="input-dark"
                  placeholder="https://facebook.com/page"
                  data-testid="settings-facebook"
                />
              </div>
              <div className="space-y-2">
                <Label className="text-white/80">{t('settings_twitter')}</Label>
                <Input
                  value={formData.social.twitter || ''}
                  onChange={(e) => setFormData({ ...formData, social: { ...formData.social, twitter: e.target.value } })}
                  className="input-dark"
                  placeholder="https://twitter.com/username"
                  data-testid="settings-twitter"
                />
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Modules Info */}
        {settings?.modules && (
          <Card className="dashboard-card">
            <CardHeader>
              <CardTitle className="text-white">Actieve Modules</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex flex-wrap gap-2">
                {Object.entries(settings.modules).map(([key, value]) => (
                  <span 
                    key={key} 
                    className={`px-3 py-1 rounded-full text-sm ${value.enabled ? 'badge-teal' : 'badge-draft'}`}
                  >
                    {key.charAt(0).toUpperCase() + key.slice(1)}
                  </span>
                ))}
              </div>
              <p className="text-white/40 text-sm mt-4">
                Neem contact op met Direct-Online om modules toe te voegen of te wijzigen.
              </p>
            </CardContent>
          </Card>
        )}

        {/* Save Button */}
        <div className="flex justify-end">
          <Button type="submit" className="btn-primary gap-2" disabled={saving} data-testid="settings-save-btn">
            <Save className="w-4 h-4" />
            {saving ? t('loading') : t('save')}
          </Button>
        </div>
      </form>
    </div>
  );
};

export default SettingsPage;
