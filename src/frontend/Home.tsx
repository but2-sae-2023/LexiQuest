import '../style/App.css';
import '../style/home.css';
import Header from './Header';
import StartButton from './StartButton';
import Deconnexion from './Deconnexion';

const Home = () => {

    return (
        <>
            <Header />
            <main>
                <StartButton />
                <Deconnexion />
            </main>
        </>
    );
};

export default Home;