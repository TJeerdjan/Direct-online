export const MODULE_ROUTE_MAP = {
  portfolio: 'portfolio.enabled',
  testimonials: 'testimonials.enabled',
  pages: 'pages.enabled',
  inbox: 'forms.enabled',
  feedback: 'feedback.enabled',
};

export const CLIENT_NAV_ITEMS = [
  { path: '/dashboard', icon: 'dashboard', label: 'nav_dashboard' },
  { path: '/portfolio', icon: 'portfolio', label: 'nav_portfolio', modulePath: MODULE_ROUTE_MAP.portfolio },
  { path: '/testimonials', icon: 'testimonials', label: 'nav_testimonials', modulePath: MODULE_ROUTE_MAP.testimonials },
  { path: '/pages', icon: 'pages', label: 'nav_pages', modulePath: MODULE_ROUTE_MAP.pages },
  { path: '/inbox', icon: 'inbox', label: 'nav_inbox', modulePath: MODULE_ROUTE_MAP.inbox },
  { path: '/feedback', icon: 'feedback', label: 'nav_feedback', modulePath: MODULE_ROUTE_MAP.feedback },
  { path: '/settings', icon: 'settings', label: 'nav_settings' },
];

export const MODULE_LABEL_MAP = {
  portfolio: 'nav_portfolio',
  testimonials: 'nav_testimonials',
  pages: 'nav_pages',
  inbox: 'nav_inbox',
  feedback: 'nav_feedback',
};
