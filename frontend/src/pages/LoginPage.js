import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useI18n } from '../context/I18nContext';
import { Button } from '../components/ui/button';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { AlertCircle } from 'lucide-react';

const LOGO_URL = "https://customer-assets.emergentagent.com/job_ghl-connect-2/artifacts/1k7uaxll_logo_direct-online.png";

const LoginPage = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const { login, language, setLanguage } = useAuth();
  const { t } = useI18n();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);

    const result = await login(email, password);
    
    if (result.success) {
      navigate('/dashboard');
    } else {
      setError(result.error || t('login_error'));
    }
    
    setLoading(false);
  };

  return (
    <div className="min-h-screen bg-[#0e172c] flex flex-col items-center justify-center p-4">
      {/* Language switcher */}
      <div className="absolute top-4 right-4">
        <div className="language-switcher">
          <button
            onClick={() => setLanguage('nl')}
            className={`language-btn ${language === 'nl' ? 'active' : ''}`}
            data-testid="login-lang-nl"
          >
            NL
          </button>
          <button
            onClick={() => setLanguage('en')}
            className={`language-btn ${language === 'en' ? 'active' : ''}`}
            data-testid="login-lang-en"
          >
            EN
          </button>
        </div>
      </div>

      <div className="w-full max-w-md animate-fadeIn">
        {/* Logo */}
        <div className="flex justify-center mb-8">
          <img 
            src={LOGO_URL} 
            alt="Direct-Online" 
            className="h-12 logo-glow"
            data-testid="login-logo"
          />
        </div>

        <Card className="bg-[#1c2336] border-white/10">
          <CardHeader className="text-center">
            <CardTitle className="text-2xl text-white">{t('login_title')}</CardTitle>
            <CardDescription className="text-white/60">{t('login_subtitle')}</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              {error && (
                <div className="flex items-center gap-2 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400" data-testid="login-error">
                  <AlertCircle className="w-4 h-4" />
                  <span className="text-sm">{error}</span>
                </div>
              )}

              <div className="space-y-2">
                <Label htmlFor="email" className="text-white/80">{t('email')}</Label>
                <Input
                  id="email"
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="naam@voorbeeld.nl"
                  required
                  className="input-dark"
                  data-testid="login-email"
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="password" className="text-white/80">{t('password')}</Label>
                <Input
                  id="password"
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••"
                  required
                  className="input-dark"
                  data-testid="login-password"
                />
              </div>

              <Button 
                type="submit" 
                className="w-full btn-primary"
                disabled={loading}
                data-testid="login-submit"
              >
                {loading ? t('loading') : t('login')}
              </Button>
            </form>
          </CardContent>
        </Card>

        <p className="text-center text-white/40 text-sm mt-6">
          {t('tagline')}
        </p>
      </div>
    </div>
  );
};

export default LoginPage;
