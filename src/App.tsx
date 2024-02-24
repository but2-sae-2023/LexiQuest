import React from 'react';
import { useState , useEffect } from 'react';
import './App.css';

function App() {
  const [value, setValue] = useState("Change me");
  const [dateTime, setDateTime] = useState<Date>(new Date);

  var foo: number = 123;

  useEffect(() => {
    const intervalId = setInterval(() => {
      setDateTime(new Date());
    }, 1000);

    return () => clearInterval(intervalId);
  }, []);

  // Fonction pour formater la date en français
  function formatDate(date: Date): string {
    const options: Intl.DateTimeFormatOptions = {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: 'numeric',
      second: 'numeric',
      timeZone: 'Europe/Paris' // Définissez le fuseau horaire approprié
    };
    return date.toLocaleString('fr-FR', options);
  }

  function handleChange(event: React.ChangeEvent<HTMLInputElement>) {
    setValue(event.currentTarget.value);
  }

  return (
    <>
      <h1>Test</h1>
      <p>Date : {formatDate(dateTime)}</p>
      <input value={value} onChange={handleChange} />
      <p>Value: {value}</p>
    </>
  );
}

export default App;
