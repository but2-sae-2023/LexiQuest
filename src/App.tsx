import { createBrowserRouter, RouterProvider } from "react-router-dom";
import './style/style.css';
import './style/App.css';
import Accueil from './frontend/Accueil';
import Compte from './frontend/Compte';
import UserProvider from './data/UserProvider';

const App = () => {
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
      <UserProvider>
        <RouterProvider router={router} />
      </UserProvider>
    </>
  );
};

export default App;
