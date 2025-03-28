import React, { useEffect } from 'react';
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
        <ChatManager socketUrl={substituteHost(process.env.REACT_APP_BACKEND_URL || 'ws:localhost:8090/chat')} />
      </div>
    </>
    
  );
}

export default App;
