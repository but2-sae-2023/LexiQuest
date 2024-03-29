import { createContext, useState, ReactNode } from "react";
import User from "../class/User";

type SetUserFunction = (user: User) => void;

// Initialisation des valeurs par défaut pour un utilisateur
const defaultUserValues = new User(
  -1, // user_id
  '', // username
  '',
  '', // email
  -1, // birth_year
  new Date(), // date_last_cnx
  new Date(), // date_signup
  -1, // nb_game_played
  -1, // avg_score
  -1, // min_score
  -1 // max_score
);

export const UserContext = createContext<{ user: User; setUser: SetUserFunction }>({
  user: defaultUserValues, // Utilisation des valeurs par défaut
  setUser: () => {}
});

interface UserProviderProps {
  children: ReactNode;
}

const UserProvider = ({ children }: UserProviderProps) => {
  const [user, setUser] = useState<User>(defaultUserValues); // Utilisation des valeurs par défaut
  
  const value = {
    user,
    setUser
  };
  
  return <UserContext.Provider value={value}>{children}</UserContext.Provider>;
};

export default UserProvider;