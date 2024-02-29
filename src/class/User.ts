export class User{
    static setUser(): import("react").SetStateAction<User> {
        throw new Error('Method not implemented.');
    }
    
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
    }

    setUser( props : {user_id:number,username:string,email:string,birth_year:number,date_last_cnx:Date,date_signup:Date,nb_game_played:number,avg_score:number,min_score:number,max_score:number}) : User{
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

    getUser_id(){
        return this.user_id;
    }
}