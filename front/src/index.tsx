import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import { createTheme, ThemeProvider } from '@mui/material/styles';
import { frFR } from '@mui/x-data-grid/locales';
import App from './App';
import './index.css';

/**
 * Thème personnalisé utilisé par MUI.
 */
const theme = createTheme(
    {},
    // Indique à MUI d'utiliser les textes français dans les composants (étiquettes, menus). MUI a déjà internationalisé ses composants.
    frFR,
);

/**
 * @see https://react.dev/reference/react-dom/client/createRoot
 */
const root = ReactDOM.createRoot(document.getElementById('root') as HTMLElement);

root.render(
    <React.StrictMode>
        {/* Passe le Routeur pour la navigation entre les pages. */}
        <BrowserRouter>
            {/* Passe la variable `theme` à toute l'application. */}
            <ThemeProvider theme={theme}>
                <App />
            </ThemeProvider>
        </BrowserRouter>
    </React.StrictMode>
)

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals