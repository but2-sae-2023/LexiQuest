export class User{
    private user_id:number;
    private username:string;
    private email:string;
    private birth_year:number;
    private date_last_cnx:Date;
    private date_signup:Date;
    private image_path:string;

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
<<<<<<< HEAD
        this.image_path = '../assets/img/default.png';
=======
        this.image_path = 'assets/img/default.png';
>>>>>>> origin/dev_loic
    }

    /* Setter de connexion */
    setConnected(co:boolean){
        this.isConnected = co;
    }

    /* Setter pour toutes les données de l'utilisateur */
    setUser(props : 
        { user_id: number,username: string,email: string,birth_year: number,date_last_cnx: Date,date_signup: Date,
            nb_game_played: number,avg_score: number,min_score: number,max_score: number }): User {
        this.user_id = props.user_id;
        this.username = props.username;
        this.email = props.email;
        this.birth_year = props.birth_year;
        this.date_last_cnx = props.date_last_cnx;
        this.date_signup = props.date_signup;
        this.nb_game_played = props.nb_game_played;
        this.avg_score = props.avg_score;
        this.min_score = props.min_score;
        this.max_score = props.max_score;
      
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
    getImagePath() { return this.image_path; }
    getConnected() { return this.isConnected; }
}

export default User;