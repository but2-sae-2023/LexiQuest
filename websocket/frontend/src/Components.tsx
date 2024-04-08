// import React, { useEffect, useState } from "react"
import React, { useEffect } from "react";
import './Components.css'
// import toast from "react-hot-toast"
import axios from "axios"
import Highcharts from 'highcharts';
// import HighchartsReact from 'highcharts-react-official';
import HCNG from 'highcharts/modules/networkgraph';
// import Graph, { DataPoint, Nodes } from "./Graph";
import Graph from "./Graph";

// Init Highcharts Networkgraph module
HCNG(Highcharts);

export interface WaitingRoom {
    name: string
    attendeeNumber: number
    description: string
}

export interface Message {
    sender: string
    timestamp: number
    content: string
}

export const Login = (props: { onLogin: (username: string) => void }) => {
    const [user, setUsername] = React.useState("")

    useEffect(() => {

        (async function () {
            try {
                const response = await axios.get('../getUser.php');
                console.log(response.data); // Vérifiez le contenu de response.data
                setUsername(response.data); // Mettez à jour le state username avec response.data
                props.onLogin(response.data);

            } catch (error) {
                console.error("Erreur lors de la récupération des données :", error);
            }
        })(); // Fonction anonyme auto-invoquée

    }, []);

    return (
        <>
            <div className="Login">
                <input type="text" value={user} placeholder="Nom d'utilisateur" onChange={event => setUsername(event.target.value)} />
                <button onClick={() => props.onLogin(user)} disabled={user === ""}>Connexion</button>
            </div>
        </>
    );
}

export const WaitingRoomSelector = (props: { rooms: WaitingRoom[], onChosenRoom: (username: string, waitingRoom: string) => void, user: string }) => {
    // const [username, setUsername] = React.useState("")
    const [selectedRoom, setSelectedRoom] = React.useState("")


    return <div className="WaintingRoomSelector">
        <div className="WaitingRoomUsername">{props.user}</div>
        <div>
            {props.rooms.map(room => <div className="RoomRadio" key={room.name}>
                <input type="radio" name="room" value={room.name} checked={selectedRoom === room.name} onChange={() => setSelectedRoom(room.name)} />
                {room.name}  ({room.attendeeNumber} joueurs)
            </div>)}
        </div>
        <button onClick={() => props.onChosenRoom(props.user, selectedRoom)} disabled={selectedRoom === "" || props.rooms.findIndex(x => x.name === selectedRoom) === -1}>Rejoindre la salle d'attente</button>
    </div>
}

export const RoomWaiter = (props: { roomName: string, startTimestamp: number, onLeaving: () => void }) => {
    const [currentTimestamp, setCurrentTimestamp] = React.useState(performance.now())
    React.useEffect(() => {
        const handle = setInterval(() => setCurrentTimestamp(performance.now()), 1000)
        return () => clearTimeout(handle)
    }, [])
    return <div className="RoomWaiter">
        <div>Attente dans la room{props.roomName} : {Math.floor((currentTimestamp - props.startTimestamp) / 1000)} s.</div>
        <div><button onClick={() => props.onLeaving()}>Quitter la salle d'attente</button></div>
    </div>
}

export const ChatMessageDisplayer = (props: { message: Message }) => {
    const date = React.useMemo(() => new Date(props.message.timestamp).toLocaleTimeString(), [props.message.timestamp])
    return <div className="ChatMessageDisplayer">
        <div>{date}</div>
        <div>{props.message.sender}</div>
        <div style={{ flex: 1 }}>{props.message.content}</div>
    </div>
}

export const ChatMessagesDisplayer = (props: { messages: Message[] }) => {
    return <ul className="messages">
        {props.messages.map((x, i) => <li key={i}><ChatMessageDisplayer message={x} /></li>)}
    </ul>
}

export const MessageSender = (props: { onMessageWritten: (content: string) => void }) => {
    const [content, setContent] = React.useState("")
    return <div className="footer">
        <input type="text" className="text-box" placeholder="Envoyer un message" value={content} style={{ flex: 1 }} onChange={event => setContent(event.target.value)} />
        <button id="sendMessage" onClick={() => { props.onMessageWritten(content); setContent('') }}></button>
    </div>
}

export const ChatSession = (props: { messages: Message[], active: boolean, onMessageWritten: (content: string) => void, onNewgame: () => void, onLeaving: () => void, onClosing: () => void }) => {
    // const chatDisplay = false;

    return (
        <div id="Chat_container">
            <div className="chat-display">
                {/* <div className="header">
                        <span className="title">
                            Chat
                        </span>
                    </div> */}
                <ChatMessagesDisplayer messages={props.messages} />
                {props.active && <MessageSender onMessageWritten={props.onMessageWritten} />}
            </div>
            <div className="chat_container_buttons">
                <button onClick={() => props.onLeaving()} disabled={!props.active}>Quitter la session</button>
                <button onClick={() => props.onClosing()} disabled={props.active}>Fermer</button>
            </div>
        </div>
    )
}

export const Word = (props: { messages: Message[], active: boolean, onMessageWritten: (content: string) => void, onLeaving: () => void, onClosing: () => void }) => {
    return <div className="ChatSession">
        <ChatMessagesDisplayer messages={props.messages} />
        {props.active && <MessageSender onMessageWritten={props.onMessageWritten} />}
        <div>
            <button onClick={() => props.onLeaving()} disabled={!props.active}>Leave the chat session</button>
            <button onClick={() => props.onClosing()} disabled={props.active}>ttt</button>
        </div>
    </div>
}

export const Game = (props: { onAddword: (word: string) => void, players: string[], chart: string }) => {
    const [content, setContent] = React.useState("")
    const Chart = JSON.parse(props.chart)

    const datas = Chart.content;
    const nodes = Chart.nodes;
    const target = Chart.target;
    const current = Chart.current;

    return <div className="Game">
        <div className="score">
            Objectif
            <div className="objective">
                {parseInt(target)}
            </div>
            Actuel
            <div className="current">
                {parseInt(current)}
            </div>
        </div>
        <div className="players">
            <p>Partie</p>
            <p>Joueurs:</p>
            <ul>
                {props.players.map((player, i) => <li key={i}>Joueur {i + 1}: {player}</li>)}
            </ul>
        </div>


        <div className="game-fields">
            <input type="text" placeholder="Insérez votre mot" value={content} style={{ flex: 1 }} onChange={event => setContent(event.target.value)} />
            <button onClick={() => { props.onAddword(content); setContent('') }}>Ajouter</button>
        </div>

        <Graph data={datas} nodes={nodes} />
    </div>

}

interface LoginState { login: true }
interface DisconnectedState { disconnected: true }
interface ConnectingState { connecting: true }
interface RoomSelectionState { roomSelection: true }
interface WaitingState { startTimestamp: number, waitingRoomName: string }
interface ChattingState { startTimestamp: number, messages: Message[], active: boolean }
type ChatState = DisconnectedState | ConnectingState | RoomSelectionState | WaitingState | ChattingState | LoginState

export const ChatManager = (props: { socketUrl: string }) => {
    const [chatState, setChatState] = React.useState<ChatState>({ connecting: true })
    const [connected, setConnected] = React.useState(false)
    const [socket, setSocket] = React.useState<WebSocket | null>(null)
    const [error, setError] = React.useState<string>('')
    const [waitingRooms, setWaitingRooms] = React.useState<WaitingRoom[]>([])
    // const [gameid, setGameid] = React.useState<string>('')
    const [username, setUsername] = React.useState<string>('')
    const [roomPlayers, setRoomPlayers] = React.useState<string[]>([])
    const [chart, setChart] = React.useState<string>('')

    const onNewSocketMessage = (kind: string, content: Record<string, any>) => {
        console.debug("Received message from websocket", content)
        const addChatMessage = (sender: string, content: string) => {
            let message: Message = { sender: sender, timestamp: Date.now(), content: content }
            setChatState(oldState => {
                if ('messages' in oldState)
                    return { ...oldState, messages: [...oldState.messages, message] }
                else return oldState
            })
        }
        const readWaitingRooms = (c: Record<string, any>) => {
            let waitingRooms = []
            for (let [name, v] of Object.entries(c['waiting_rooms'])) {
                let value = v as any
                let room: WaitingRoom = { name: name, attendeeNumber: value.attendee_number, description: value.description }
                waitingRooms.push(room)
            }
            return waitingRooms
        }

        switch (kind) {
            case 'login_required':
                setChatState({ login: true })
                break

            case 'login_ok':
                setUsername(content.username)
                console.log("connecté certifié par webSocket");
                break

            case 'waiting_room_list':
                setWaitingRooms(readWaitingRooms(content))
                setChatState({ roomSelection: true })
                break

            case 'in_waiting_room':
                let name = content.waiting_room_name
                setChatState({ waitingRoomName: name, startTimestamp: performance.now() })
                break

            case 'waiting_room_left':
                setChatState({ roomSelection: true })
                break

            case 'waiting_room_join_refused':
                setError(`Cannot join the room: ${content.reason}`)
                break

            case 'chat_session_started':
                setChatState({ startTimestamp: performance.now(), messages: [], active: true })
                addChatMessage('admin', content.welcome_message)
                // addChatMessage('id', content.gameid)
                setRoomPlayers(content.players)
                break

            case 'new_game_started':
                setChart(content.result)
                break

            case 'word_added':
                setChart(content.result)
                break


            case 'chat_message_received':
                addChatMessage(content.sender, content.content)
                break

            case 'attendee_left':
                addChatMessage('admin', `L'utilisateur ${content.attendee} a quitté la session.`)
                break

            case 'chat_session_left':
                setChatState(oldState => ('messages' in oldState) ? { ...oldState, active: false } : oldState)
                break

            case 'chat_session_ended':
                setChatState(oldState => ('messages' in oldState) ? { ...oldState, active: false } : oldState)
                addChatMessage('admin', "End of the chat session due to time limit.")
                addChatMessage('admin', content.exit_message)
                break

            case 'server_shutdown':
                setError('Server will shutdown now! Please reconnect later.')
                break

            default:
                setError(`Received non understable message: kind=${kind} content=${JSON.stringify(content)}`)
        }
    }

    const sendToSocket = React.useCallback((kind: string, body: Record<string, any>) => {
        const to_send = { kind: kind, ...body }
        const stringified = JSON.stringify(to_send)
        console.debug(`Sending message on the websocket`, to_send)
        socket?.send(stringified)
    }, [socket])

    const connectToWaitingRoom = React.useCallback((username: string, waitingRoomName: string) => {
        sendToSocket('join_waiting_room', { 'token': username, 'waiting_room_name': waitingRoomName })
    }, [sendToSocket])
    const leaveWaitingRoom = React.useCallback(() => {
        sendToSocket('leave_waiting_room', {})
    }, [sendToSocket])
    const sendChatMessage = React.useCallback((content: string) => {
        sendToSocket('send_chat_message', { content: content })
    }, [sendToSocket])
    const leaveChatSession = React.useCallback(() => {
        sendToSocket('leave_chat_session', {})
    }, [sendToSocket])
    const closeChatSession = React.useCallback(() => {
        setChatState({ roomSelection: true })
    }, [])
    const new_game = React.useCallback(() => {
        sendToSocket('new_game', {})
    }, [sendToSocket])
    const login = React.useCallback((username: string) => {
        sendToSocket('login', { user: username })
    }, [sendToSocket])
    const addWord = React.useCallback((word: string, username: string) => {
        sendToSocket('add_word', { word: word, user: username })
    }, [sendToSocket])

    useEffect(() => {
        if ('connecting' in chatState) {
            setConnected(true)
        } else if ('disconnected' in chatState) {
            setConnected(false)
        }
    }, [chatState])

    // create and configure a websocket
    useEffect(() => {
        if (connected) {
            console.debug(`Opening the websocket with the URL ${props.socketUrl}`)
            const newSocket = new WebSocket(props.socketUrl)
            setSocket(newSocket)
            newSocket.addEventListener('open', () => {
                setChatState({ login: true })
            })
            newSocket.addEventListener('login_ok', () => {
                setChatState({ roomSelection: true })
            })
            newSocket.addEventListener('message', (event) => {
                const data = event.data
                if (typeof (data) === 'string') {
                    let json = null
                    let kind = null
                    try {
                        json = JSON.parse(data)
                        kind = json['kind']
                    } catch {
                        console.error("Received invalid JSON", data)
                    }
                    if (json !== null && kind !== null)
                        onNewSocketMessage(kind, json)
                }
            })
            newSocket.addEventListener('error', (event) => {
                console.error("WebSocket error", event)
                setChatState({ disconnected: true })
                setError(`Websocket connection error: ${event}`)
            })
            newSocket.addEventListener('close', (event) => {
                console.error("WebSocket closed", event)
                setChatState({ disconnected: true })
            })
            // close the socket
            return () => {
                newSocket.close()
                setWaitingRooms([])
                setSocket(null)
            }
        }
    }, [connected, props.socketUrl])

    return (<div className="ChatManager">
        {error !== '' &&
            <div className="Error">Error: {error} <button onClick={() => setError('')}>OK</button></div>}
        {'disconnected' in chatState &&
            <div className="Disconnected">
                <div>Disconnected</div>
                <button onClick={() => setChatState({ connecting: true })}>Connect now</button></div>}
        {'connecting' in chatState &&
            <div className="Connecting">
                <div>Connecting to server {props.socketUrl}</div>
            </div>}
        {'login' in chatState &&
            <Login onLogin={login} />}
        {'roomSelection' in chatState &&
            <WaitingRoomSelector rooms={waitingRooms} onChosenRoom={connectToWaitingRoom} user={username} />}
        {'waitingRoomName' in chatState &&
            <RoomWaiter roomName={chatState.waitingRoomName} startTimestamp={chatState.startTimestamp} onLeaving={leaveWaitingRoom} />}
        {'messages' in chatState &&
            <ChatSession messages={chatState.messages} active={chatState.active} onMessageWritten={sendChatMessage} onNewgame={new_game} onLeaving={leaveChatSession} onClosing={closeChatSession} />
        }
        {'messages' in chatState &&
            <Game onAddword={word => addWord(word, username)} players={roomPlayers} chart={chart} />}
    </div>);
}