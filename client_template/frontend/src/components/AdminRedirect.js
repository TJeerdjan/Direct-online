// ================================================================
// ADMIN REDIRECT COMPONENT
// ================================================================
// 
// Redirects /admin to Direct-Online Dashboard
// Client manages their content there instead of a built-in admin
//
// ================================================================

import React, { useEffect } from "react";

const DASHBOARD_URL = "https://ghl-connect-2.preview.emergentagent.com";

const AdminRedirect = () => {
  useEffect(() => {
    // Redirect to Direct-Online dashboard
    window.location.href = DASHBOARD_URL;
  }, []);

  return (
    <div style={{ 
      minHeight: '100vh', 
      display: 'flex', 
      alignItems: 'center', 
      justifyContent: 'center',
      paddingTop: '4rem'
    }}>
      <div style={{ textAlign: 'center' }}>
        <div className="spinner" style={{ margin: '0 auto 1.5rem' }} />
        <h2 style={{ marginBottom: '0.5rem' }}>Doorsturen naar Dashboard...</h2>
        <p className="text-muted" style={{ marginBottom: '1.5rem' }}>
          Je wordt doorgestuurd naar het Direct-Online dashboard
        </p>
        <a 
          href={DASHBOARD_URL}
          className="btn btn-primary"
        >
          Klik hier als je niet wordt doorgestuurd
        </a>
      </div>
    </div>
  );
};

export default AdminRedirect;
