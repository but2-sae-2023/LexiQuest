import './header.css';
import logo from './logo.png';


const Header = () => {

  return (
    <>
      <header>
        <img src={logo} alt="logo de LexiQuest" />
        
        <h1>LexiQuest</h1>
      </header>
    </>
  );
};

export default Header;
