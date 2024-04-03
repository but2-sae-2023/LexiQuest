import React, { useEffect, useState } from "react"

import './Components.css'

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

export const Login = (props: {onLogin: (username: string, password: string) => void}) => {
    const [username, setUsername] = React.useState("")
    const [password, setPassword] = React.useState("")
    return <div className="Login">
        <div>Username: <input type="text" value={username} onChange={event => setUsername(event.target.value)} /></div>
        <div>Password: <input type="password" value={password} onChange={event => setPassword(event.target.value)} /></div>
        <button onClick={() => props.onLogin(username, password)} disabled={username === "" || password === ""}>Login</button>
    </div>

}

export const WaitingRoomSelector = (props: {rooms: WaitingRoom[], onChosenRoom: (username: string, waitingRoom: string) => void, user: string}) => {
    const [username, setUsername] = React.useState("")
    const [selectedRoom, setSelectedRoom] = React.useState("")
    return <div className="WaintingRoomSelector">
        <div>Username: {props.user}</div>
        <div>
            {props.rooms.map(room => <div key={room.name}>
                <input type="radio" name="room" value={room.name} checked={selectedRoom === room.name} onChange={() => setSelectedRoom(room.name)} />
                {room.name}  ({room.attendeeNumber} joueurs )
            </div>)}
        </div>
        <button onClick={() => props.onChosenRoom(props.user, selectedRoom)} disabled={ selectedRoom === "" || props.rooms.findIndex(x => x.name === selectedRoom) === -1}>Join the waiting room</button>
    </div>
}

export const RoomWaiter = (props: {roomName: string, startTimestamp: number, onLeaving: () => void}) => {
    const [currentTimestamp, setCurrentTimestamp] = React.useState(performance.now())
    React.useEffect(() => {
        const handle = setInterval(() => setCurrentTimestamp(performance.now()), 1000)
        return () => clearTimeout(handle)
    }, [])
    return <div className="RoomWaiter">
        <div>Waiting in room {props.roomName} for {Math.floor((currentTimestamp - props.startTimestamp) / 1000)} s.</div>
        <div><button onClick={() => props.onLeaving() }>Leave the waiting room</button></div>
    </div>
}

export const ChatMessageDisplayer = (props: {message: Message}) => {
    const date = React.useMemo(() => new Date(props.message.timestamp).toLocaleTimeString(), [props.message.timestamp])
    return <div className="ChatMessageDisplayer">
        <div>{date}</div>
        <div>{props.message.sender}</div>
        <div style={{flex: 1}}>{props.message.content}</div>
    </div>
}

export const ChatMessagesDisplayer = (props: {messages: Message[]}) => {
    return <ul className="messages">
        {props.messages.map((x, i) => <li key={i} className={"" /*x.sender == username ? "self" : "other"*/}><ChatMessageDisplayer message={x} /></li>)}
    </ul>
}

export const MessageSender = (props: {onMessageWritten: (content: string) => void}) => {
    const [content, setContent] = React.useState("")
    return <div className="footer">
        <input type="text" className="text-box" value={content} style={{flex: 1}} onChange={event => setContent(event.target.value)} />
        <button id="sendMessage" onClick={() => {props.onMessageWritten(content); setContent('')}}>Send</button>
    </div>
}

export const ChatSession = (props: {messages: Message[], active: boolean, onMessageWritten: (content: string) => void, onNewgame: () => void ,onLeaving: () => void, onClosing: () => void}) => {
    const [chatDisplay, setChatDisplay] = useState(false);

    function displayChat(){
        setChatDisplay(!chatDisplay);
    }
    
    return(
        <div id="Chat_container">
            <div className={chatDisplay ? "chat active" : "chat"} onClick={displayChat}>
                <div className="background"></div>
                <svg className="chat-bubble" width="100" height="100" viewBox="0 0 100 100">
                    <g className="bubble">
                    <path className="line line1" d="M 30.7873,85.113394 30.7873,46.556405 C 30.7873,41.101961
                    36.826342,35.342 40.898074,35.342 H 59.113981 C 63.73287,35.342
                    69.29995,40.103201 69.29995,46.784744" />
                    <path className="line line2" d="M 13.461999,65.039335 H 58.028684 C
                        63.483128,65.039335
                        69.243089,59.000293 69.243089,54.928561 V 45.605853 C
                        69.243089,40.986964 65.02087,35.419884 58.339327,35.419884" />
                    </g>
                    <circle className="circle circle1" r="1.9" cy="50.7" cx="42.5" />
                    <circle className="circle circle2" cx="49.9" cy="50.7" r="1.9" />
                    <circle className="circle circle3" r="1.9" cy="50.7" cx="57.3" />
                </svg>
            </div>

            <div className={chatDisplay ? "floating-chat-active" : "floating-chat"}>
                <div className="chat-display">
                    <div className="header">
                        <span className="title">
                            Chat
                        </span>
                    </div>
                    <ChatMessagesDisplayer messages={props.messages} />
                    {props.active && <MessageSender onMessageWritten={props.onMessageWritten} />}
                </div>
            </div>
            <div>
                <button onClick={() => props.onLeaving()} disabled={!props.active}>Leave the chat session</button>
                <button onClick={() => props.onClosing()} disabled={props.active}>Clostttftye</button>
            </div>
        </div>
    )
}

export const Word = (props: {messages: Message[], active: boolean, onMessageWritten: (content: string) => void, onLeaving: () => void, onClosing: () => void}) => {
    return <div className="ChatSession">
        <ChatMessagesDisplayer messages={props.messages} />
        {props.active && <MessageSender onMessageWritten={props.onMessageWritten} />}
        <div>
            <button onClick={() => props.onLeaving()} disabled={!props.active}>Leave the chat session</button>
            <button onClick={() => props.onClosing()} disabled={props.active}>ttt</button>
        </div>
    </div>
}

export const Game = (props: {onAddword: (word: string) => void,  players: string[]}) => {
    const [content, setContent] = React.useState("")
    return <div className="Game">
        <p>Partie</p>
        <p>Joueurs:</p>
        <ul>
            {props.players.map((player, i) => <li key={i}>Joueur {i+1}: {player}</li>)}
        </ul>
        <input type="text" value={content} style={{flex: 1}} onChange={event => setContent(event.target.value)} />
        <button onClick={() => {props.onAddword(content); setContent('')}}>Send</button>
    </div>

}

interface LoginState { login: true }
interface DisconnectedState { disconnected: true }
interface ConnectingState { connecting: true }
interface RoomSelectionState { roomSelection: true }
interface WaitingState { startTimestamp: number, waitingRoomName: string }
interface ChattingState { startTimestamp: number, messages: Message[], active: boolean }
type ChatState = DisconnectedState | ConnectingState | RoomSelectionState | WaitingState | ChattingState | LoginState

export const ChatManager = (props: {socketUrl: string}) => {
    const [chatState, setChatState] = React.useState<ChatState>({disconnected: true})
    const [connected, setConnected] = React.useState(false)
    const [socket, setSocket] = React.useState<WebSocket|null>(null)
    const [error, setError] = React.useState<string>('')
    const [waitingRooms, setWaitingRooms] = React.useState<WaitingRoom[]>([])
    const [gameid, setGameid] = React.useState<string>('')
    const [username, setUsername] = React.useState<string>('')
    const [roomPlayers, setRoomPlayers] = React.useState<string[]>([])

    const onNewSocketMessage = (kind: string, content: Record<string, any>) => {
        console.debug("Received message from websocket", content)
        const addChatMessage = (sender: string, content: string) => {
            let message: Message = {sender: sender, timestamp: Date.now(), content: content}
            setChatState(oldState => {
                if ('messages' in oldState)
                    return {...oldState, messages: [...oldState.messages, message]}
                else return oldState
            })
        }
        const readWaitingRooms = (c: Record<string, any>) => {
            let waitingRooms = []
            for (let [name, v] of Object.entries(c['waiting_rooms'])) {
                let value = v as any
                let room: WaitingRoom = {name: name, attendeeNumber: value.attendee_number, description: value.description}
                waitingRooms.push(room)
            }
            return waitingRooms
        }

        switch(kind) {
            case 'login_required':
                setChatState({login: true})
                break
            
            case 'login_ok':
                setUsername(content.username)
                break

            case 'waiting_room_list':
                setWaitingRooms(readWaitingRooms(content))
                setChatState({roomSelection: true})
                break
            
            case 'in_waiting_room':
                let name = content.waiting_room_name
                setChatState({waitingRoomName: name, startTimestamp: performance.now()})
                break

            case 'waiting_room_left':
                setChatState({roomSelection: true})
                break

            case 'waiting_room_join_refused':
                setError(`Cannot join the room: ${content.reason}`)
                break

            case 'chat_session_started':
                setChatState({startTimestamp: performance.now(), messages: [], active: true})
                addChatMessage('admin', content.welcome_message)
                addChatMessage('id', content.gameid)
                setRoomPlayers(content.players)
                break

            case 'chat_message_received':
                addChatMessage(content.sender, content.content)
                break

            case 'attendee_left':
                addChatMessage('admin', `Attendee ${content.attendee} left the chat session.`)
                break

            case 'chat_session_left':
                setChatState(oldState => ('messages' in oldState)?{...oldState, active: false}:oldState)
                break

            case 'chat_session_ended':
                setChatState(oldState => ('messages' in oldState)?{...oldState, active: false}:oldState)
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
        const to_send = {kind: kind, ...body}
        const stringified = JSON.stringify(to_send)
        console.debug(`Sending message on the websocket`, to_send)
        socket?.send(stringified)
    }, [socket])

    const connectToWaitingRoom = React.useCallback((username: string, waitingRoomName: string) => {
        sendToSocket('join_waiting_room', {'token': username, 'waiting_room_name': waitingRoomName})
    }, [sendToSocket])
    const leaveWaitingRoom = React.useCallback(() => {
        sendToSocket('leave_waiting_room', {})
    }, [sendToSocket])
    const sendChatMessage = React.useCallback((content: string) => {
        sendToSocket('send_chat_message', {content: content})
    }, [sendToSocket])
    const leaveChatSession = React.useCallback(() => {
        sendToSocket('leave_chat_session', {})
    }, [sendToSocket])
    const closeChatSession = React.useCallback(() => {
        setChatState({roomSelection: true})
    }, [])
    const new_game = React.useCallback(() => {
        sendToSocket('new_game',{})
    }, [sendToSocket])
    const login= React.useCallback((username: string, password: string) => {
        sendToSocket('login', {user: username, password: password})
    }, [sendToSocket])
    const addWord = React.useCallback((word: string, username: string) => {
        sendToSocket('add_word', {word: word, user: username})    
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
            newSocket.addEventListener('open', (event) => {
                setChatState({login: true})
            })
            newSocket.addEventListener('login_ok', (event)=> {
                setChatState({roomSelection: true})
            })
            newSocket.addEventListener('message', (event) => {
                const data = event.data
                if (typeof(data) === 'string') {
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
                setChatState({disconnected: true})
                setError(`Websocket connection error: ${event}`)
            })
            newSocket.addEventListener('close', (event) => {
                console.error("WebSocket closed", event)
                setChatState({disconnected: true})
            })
            // close the socket
            return () => {
                newSocket.close()
                setWaitingRooms([])
                setSocket(null)
            }
        }
    }, [connected, props.socketUrl])

    return <div className="ChatManager">
        {error !== '' && 
            <div className="Error">Error: {error} <button onClick={() => setError('')}>OK</button></div>}
        {'disconnected' in chatState && 
            <div className="Disconnected">
                <div>Disconnected</div>
                <button onClick={() => setChatState({connecting: true})}>Connect now</button></div>}
        {'connecting' in chatState &&
            <div className="Connecting">
                <div>Connecting to server {props.socketUrl}</div>
            </div>}
        {'login' in chatState &&
            <Login onLogin={login}/>}
        {'roomSelection' in chatState && 
            <WaitingRoomSelector rooms={waitingRooms} onChosenRoom={connectToWaitingRoom} user={username} />}
        {'waitingRoomName' in chatState &&
            <RoomWaiter roomName={chatState.waitingRoomName} startTimestamp={chatState.startTimestamp} onLeaving={leaveWaitingRoom} />}
        {'messages' in chatState && 
            <ChatSession messages={chatState.messages} active={chatState.active} onMessageWritten={sendChatMessage} onNewgame={new_game} onLeaving={leaveChatSession} onClosing={closeChatSession} />
        }
        {'messages' in chatState &&
            <Game onAddword={word => addWord(word, username)} players={roomPlayers} />}
    </div>
}