import './App.css';
import Header from './Header';
import { ChatManager } from './Components';
import { Toaster } from 'react-hot-toast';
function substituteHost(s: string): string {
  return s.replace('myhost', document.location.host).replace('myprotocol', document.location.protocol === 'http:' ? 'ws:' : 'wss:');
}

function App() {

  return (
    <>
      <Toaster
        position="bottom-left"
        reverseOrder={false}
      />
      <div className="App">
        <Header />
        <ChatManager socketUrl={substituteHost('ws:localhost:8090/chat')} />
      </div>
      <a href="../../../frontend/home.php">
        <div className="back"></div>
      </a>
    </>

  );
}

export default App;
