import './header.css';
import logo from './logo.png';


const Header = () => {

  return (
    <>
      <div className="logo">
          <img src={logo} alt="logo" />
        </div>
      <div className="header"> 
        <h1>LexiQuest</h1>
      </div>
    </>
  );
};

export default Header;
