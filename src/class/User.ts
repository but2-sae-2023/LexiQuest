export class User{

    private user_id:number;
    private username:string;
    private email:string;
    private birth_year:number;
    private date_last_cnx:Date;
    private date_signup:Date;

    private nb_game_played:number;
    private avg_score:number;
    private min_score:number;
    private max_score:number;
    private isConnected:boolean;

    constructor(){
        this.user_id = -1;
        this.username = "";
        this.email = "";
        this.birth_year = -1;
        this.date_last_cnx = new Date();
        this.date_signup = new Date();

        this.nb_game_played = -1;
        this.avg_score = -1;
        this.min_score = -1;
        this.max_score = -1;
        this.isConnected = false;
    }

    /* Setter de connexion */
    setConnected(co:boolean){
        this.isConnected = co;
    }

    /* Setter pour toutes les données de l'utilisateur */
    setUser({ user_id,username,email,birth_year,date_last_cnx,date_signup,nb_game_played,avg_score,min_score,max_score }: User): User {
        this.user_id = user_id;
        this.username = username;
        this.email = email;
        this.birth_year = birth_year;
        this.date_last_cnx = date_last_cnx;
        this.date_signup = date_signup;
        this.nb_game_played = nb_game_played;
        this.avg_score = avg_score;
        this.min_score = min_score;
        this.max_score = max_score;
      
        return this;
    }

    /* Getter */
    getUser_id() { return this.user_id; }
    getUsername() { return this.username; }
    getEmail() { return this.email; }
    getBirth_year() { return this.birth_year; }
    getDate_last_cnx() { return this.date_last_cnx; }
    getDate_signup() { return this.date_signup; }
    getNb_game_played() { return this.nb_game_played; }
    getAvg_score() { return this.avg_score; }
    getMin_score() { return this.min_score; }
    getMax_score() { return this.max_score; }
    getConnected() { return this.isConnected; }
}

export default User;