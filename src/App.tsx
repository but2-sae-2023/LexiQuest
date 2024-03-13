import React, { createContext, ReactNode, useState } from 'react';
import { createBrowserRouter, RouterProvider } from "react-router-dom";
import './style/App.css';
import './style/header.css';
import Accueil from './frontend/Accueil';
import Compte from './frontend/Compte';
import User from './class/User';

export const UserContext = createContext<User>(new User());

const App = () => {
  const [displayConnexion, setdisplayConnexion] = useState(true);
 
  const router = createBrowserRouter([
    {
      path: "/",
      element: <Accueil />,
    },
    {
      path: "/compte",
      element: <Compte />,
    }
  ]);

  return (
    <>
      <RouterProvider router={router} />
    </>
  );
};

export default App;