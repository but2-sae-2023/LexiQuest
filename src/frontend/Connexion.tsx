import React, { useContext, useState } from 'react';

const RequisMdp = {
    caracteres12: "12 caractères",
    majuscule: "Majuscule",
    minuscule: "Minuscule",
    caractereSpecial: "Caractère spécial",
    chiffre: "Chiffre"
};

const Connexion: React.FC = () => {
    //useContext(UserContext);
    const [pseudo, setPseudo] = useState('');
    const [mdp, setMdp] = useState('');

    const verifierRequisMdp = (mdp:string) => {
        const critereRequis = Object.values(RequisMdp);
        const resultat = critereRequis.map(critere => ({ critere, accepte: false }));

        if (mdp.length >= 12) resultat[0].accepte = true;
        if (/[A-Z]/.test(mdp)) resultat[1].accepte = true;
        if (/[a-z]/.test(mdp)) resultat[2].accepte = true;
        if (/[!@#$%^&*(),.?":{}|<>]/.test(mdp)) resultat[3].accepte = true;
        if (/\d/.test(mdp)) resultat[4].accepte = true;

        return resultat;
    };

    const afficherRequisMdp = (mdp:string) => {
        const criteres = verifierRequisMdp(mdp);
        return criteres.map(({ critere, accepte }, index) => (
            <>
                <span key={index} id='criteres' style={{ color: accepte ? '#22BD43' : '#393838' }}>  {"\u2714 " + critere} </span>
            </>
        ));
    };

    return (
        <div className="container">
            <form method="post">
                <h1>Connexion</h1>
                <div className="input">
                    <input type="text" onChange={(event) => setPseudo(event.target.value)} value={pseudo} name="user" id="user" placeholder="Nom d'utilisateur" required />
                </div>
                <div className="input">
                    <input type="password" onChange={(event) => setMdp(event.target.value)} value={mdp} name="pwd" id="pwd" placeholder="Mot de passe" required />
                    <div id='attendu'>
                        {afficherRequisMdp(mdp)}
                    </div>
                    <p><a href="../backend/forgotPwd.php">Mot de passe oublié ?</a></p>
                </div>
                <input type="submit" id="submit" value="SE CONNECTER" />
                <div className="options">
                    <p>Vous n'avez pas de compte ?</p>
                </div>
            </form>
        </div>
    );
}

export default Connexion;