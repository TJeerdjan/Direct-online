import React, { useState } from 'react';
import { Outlet, NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { useI18n } from '../context/I18nContext';
import {
  LayoutDashboard,
  Briefcase,
  MessageSquareQuote,
  FileText,
  Inbox,
  MessageCircle,
  Settings,
  Users,
  LogOut,
  Menu,
  X,
  ChevronDown
} from 'lucide-react';
import { Button } from '../components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '../components/ui/dropdown-menu';

const LOGO_URL = "https://customer-assets.emergentagent.com/job_ghl-connect-2/artifacts/1k7uaxll_logo_direct-online.png";

const DashboardLayout = () => {
  const { user, logout, isAdmin, language, setLanguage } = useAuth();
  const { t } = useI18n();
  const navigate = useNavigate();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  const clientNavItems = [
    { path: '/dashboard', icon: LayoutDashboard, label: 'nav_dashboard' },
    { path: '/portfolio', icon: Briefcase, label: 'nav_portfolio' },
    { path: '/testimonials', icon: MessageSquareQuote, label: 'nav_testimonials' },
    { path: '/pages', icon: FileText, label: 'nav_pages' },
    { path: '/inbox', icon: Inbox, label: 'nav_inbox' },
    { path: '/feedback', icon: MessageCircle, label: 'nav_feedback' },
    { path: '/settings', icon: Settings, label: 'nav_settings' },
  ];

  const adminNavItems = [
    { path: '/dashboard', icon: LayoutDashboard, label: 'nav_dashboard' },
    { path: '/admin/clients', icon: Users, label: 'nav_clients' },
    { path: '/admin/feedback', icon: MessageCircle, label: 'feedback_title' },
    { path: '/settings', icon: Settings, label: 'nav_settings' },
  ];

  const navItems = isAdmin ? adminNavItems : clientNavItems;

  return (
    <div className="flex h-screen bg-[#0e172c]">
      {/* Mobile sidebar overlay */}
      {sidebarOpen && (
        <div 
          className="fixed inset-0 bg-black/50 z-40 lg:hidden"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* Sidebar */}
      <aside className={`
        fixed lg:static inset-y-0 left-0 z-50
        w-64 sidebar flex flex-col
        transform transition-transform duration-200 ease-in-out
        ${sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
      `}>
        {/* Logo */}
        <div className="p-4 border-b border-white/5">
          <img 
            src={LOGO_URL} 
            alt="Direct-Online" 
            className="h-10 logo-glow"
            data-testid="sidebar-logo"
          />
        </div>

        {/* Navigation */}
        <nav className="flex-1 py-4 overflow-y-auto">
          <ul className="space-y-1 px-2">
            {navItems.map((item) => (
              <li key={item.path}>
                <NavLink
                  to={item.path}
                  onClick={() => setSidebarOpen(false)}
                  className={({ isActive }) =>
                    `sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg ${isActive ? 'active' : ''}`
                  }
                  data-testid={`nav-${item.label}`}
                >
                  <item.icon className="w-5 h-5" />
                  <span>{t(item.label)}</span>
                </NavLink>
              </li>
            ))}
          </ul>
        </nav>

        {/* User section */}
        <div className="p-4 border-t border-white/5">
          {/* Language switcher */}
          <div className="language-switcher mb-4">
            <button
              onClick={() => setLanguage('nl')}
              className={`language-btn ${language === 'nl' ? 'active' : ''}`}
              data-testid="lang-nl"
            >
              NL
            </button>
            <button
              onClick={() => setLanguage('en')}
              className={`language-btn ${language === 'en' ? 'active' : ''}`}
              data-testid="lang-en"
            >
              EN
            </button>
          </div>

          {/* User info */}
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <button className="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition-colors" data-testid="user-menu">
                <div className="w-10 h-10 rounded-full bg-[#129387] flex items-center justify-center text-white font-medium">
                  {user?.name?.charAt(0).toUpperCase()}
                </div>
                <div className="flex-1 text-left">
                  <p className="text-sm font-medium text-white truncate">{user?.name}</p>
                  <p className="text-xs text-white/50 truncate">{user?.email}</p>
                </div>
                <ChevronDown className="w-4 h-4 text-white/50" />
              </button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56 bg-[#1c2336] border-white/10">
              <DropdownMenuItem 
                onClick={handleLogout}
                className="text-red-400 focus:text-red-400 focus:bg-red-400/10 cursor-pointer"
                data-testid="logout-btn"
              >
                <LogOut className="w-4 h-4 mr-2" />
                {t('logout')}
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </aside>

      {/* Main content */}
      <main className="flex-1 flex flex-col overflow-hidden">
        {/* Top bar (mobile) */}
        <header className="lg:hidden flex items-center justify-between p-4 border-b border-white/5 bg-[#1c2336]">
          <button
            onClick={() => setSidebarOpen(true)}
            className="p-2 text-white/70 hover:text-white"
            data-testid="mobile-menu-btn"
          >
            <Menu className="w-6 h-6" />
          </button>
          <img src={LOGO_URL} alt="Direct-Online" className="h-8" />
          <div className="w-10" /> {/* Spacer */}
        </header>

        {/* Page content */}
        <div className="flex-1 overflow-y-auto p-4 lg:p-8">
          <Outlet />
        </div>
      </main>
    </div>
  );
};

export default DashboardLayout;
