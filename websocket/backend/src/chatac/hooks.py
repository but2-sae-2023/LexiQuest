#! /usr/bin/env python3

from typing import Dict, List, Any, Union
import urllib
import requests
import json

class ChatHooks(object):
    """
    Hook methods for the chatac server.
    Please inherit this class to implement the methods.
    All the methods are asynchronous; methods may invoke processes, web requests if required...
    Be careful to manage exceptions inside the methods: a hook method must not raise an exception.
    A hook method must also be executed in a reasonable time (less than a few seconds).
    Use timeouts if you can another async functions with a possible long run time.
    """
    async def on_server_start(self, params: Dict[str, Any]) -> List[Dict[str, Any]]:
        """
        Called when the server starts.
        This method must return a dictionary of the waiting rooms (the key is the name of the room, the value the parameters).
        Waiting parameters are defined by a dictionary containing at least:
        - a field 'attendee_number' with the number of attendee in each chat session
        - an optional field 'description' with a detailed description of the room
        """
        raise NotImplementedError()

    async def on_client_connection(self, waiting_room_name: str, token: str) -> Union[dict, None, str]:
        """
        Return the identity of the client for the given token.
        The token is used to authenticate the client.
        If the token is valid, this hook must return the identity of the client.
        The identity is a Python dictionary containing at least a field 'name' with the display name of the client.
        If the token is invalid or if the client is not granted in the waiting room for any reason, 
        this hook must return None or a string with the reason of the invalidity.
        """
        raise NotImplementedError()

    async def on_chat_session_start(self, waiting_room_name: str, chat_session_id: int, attendee_identities: Dict[int, Dict[str, Any]]) -> Dict[str, Any]:
        """
        This hook is called when a new chat session starts.
        The waiting_room_index is the index of the waiting room used for this session;
        the index is the position of the room in the list returned by on_server_start.
        The chat_session_id is an integer identifying the chat session.
        The attendee_identities is a dict with the id of the attendees as keys and the identities of the attendees as values.
        The result of this method is a dictionary containing the following elements:
        - a field 'welcome_message' with a welcome message that will be transmitted to the attendees
        - a field 'duration' with the duration (in seconds) of the chat session
        """
        raise NotImplementedError()

    async def on_chat_message(self, chat_session_id: int, sender_id: int, content: Any) -> Dict[int, Any]:
        """
        This hook is triggered when a new chat message is sent by an attendee of the chat session.
        The sender_index is the index of the sender in the attendee_identities parameter of tne on_new_chat_session method.
        The content of the message is a data structure.
        It returns a dictionary; each key is a attendee index linked to the message that must be relayed to this attendee.
        This way we can filter and/or modify the messages that can be retransmitted.
        """
        raise NotImplementedError()

    async def on_attendee_leave(self, chat_session_id: int, attendee_id: int):
        """
        This hook is called when an attendee leaves the chat session.
        """
        raise NotImplementedError()

    async def on_chat_session_end(self, chat_session_id: int) -> Any:
        """
        This hook is called when the chat session ends.
        The result of this method is a structure that is transmitted as an exit message to all the attendees.
        """
        raise NotImplementedError()


class DefaultChatHooks(ChatHooks):
    DEFAULT_WELCOME_MESSAGE = "Welcome everybody!"
    DEFAULT_DURATION = 60
    DEFAULT_ROOMS ={"Serveur #1": {"attendee_number": 2, "duration": 1000, "welcome_message": "Bienvenue!"},
                    "Serveur #2": {"attendee_number": 2, "duration": 1000, "welcome_message": "Bienvenue!"},
                    "Serveur #3": {"attendee_number": 2, "duration": 1000, "welcome_message": "Bienvenue!"},
                    "Serveur #4": {"attendee_number": 1, "duration": 600, "welcome_message": "Bienvenue!"},
    } 
    DEF_ID=0

    class AttendeeInfo(object):
        def __init__(self, identity):
            self.identity = identity
            self.has_left = False
            self.message_number = 0
            self.char_number = 0
            self.username=''

    def __init__(self):
        self._rooms: Dict[str, Dict[str, Any]] = {}
        self._attendees: Dict[int, List[AttendeeInfo]] = {}

    async def on_server_start(self, params: Dict[str, Any]) -> List[Dict[str, Any]]:
        self._rooms = params.get('rooms', self.DEFAULT_ROOMS)
        return self._rooms

    async def on_client_connection(self, waiting_room_name: str, token: str) -> Union[dict, None, str]:
        """We consider that the token is the nickname that is proposed by the user"""
        if token.strip() == '':
            return "the nickname cannot be empty"
        elif waiting_room_name not in self._rooms:
            return f"the waiting room {waiting_room_name} is unknown"
        else:
            return {"name": token}

    async def on_chat_session_start(self, waiting_room_name: str, chat_session_id: int, attendee_identities: Dict[int, Dict[str, Any]]) -> Any:
        self._attendees[chat_session_id] = {id: self.AttendeeInfo(x) for (id, x) in attendee_identities.items()}
        room = self._rooms[waiting_room_name]
        return {
            "welcome_message": room.get("welcome_message", self.DEFAULT_WELCOME_MESSAGE), 
            "duration": self._rooms[waiting_room_name].get("duration", self.DEFAULT_DURATION)
        }

    async def on_chat_message(self, chat_session_id: int, sender_id: int, content: Any) -> Dict[int, Any]:
        # update the stats

        attendee = self._attendees[chat_session_id][sender_id]
        attendee.message_number += 1
        attendee.char_number += len(str(content))

        i = 0
        result = {}
        for (id, a) in self._attendees[chat_session_id].items():
            if not a.has_left:
                result[id] = content
        return result

    async def on_attendee_leave(self, chat_session_id: int, attendee_id: int):
        self._attendees[chat_session_id][attendee_id].has_left = True

    async def on_chat_session_end(self, chat_session_id: int) -> Any:
        """Send the stats for the session"""
        attendees = self._attendees[chat_session_id]
        stats = [f"{a.identity['name']} sent {a.message_number} messages with {a.char_number} chars" for a in attendees.values()] 
        joined = "\n".join(stats)
        return f"Did you know that: {joined}"

    async def on_login(self, user: str, password: str):
        url = 'https://perso-etudiant.u-pem.fr/~rabah.cherak/LexiQuest-bis/multi/login.php'
        query = {'user': user, 'pwd': password}
        res = requests.post(url, query, headers={"User-Agent": "Firefox/12.0"})
        if res.status_code == 200:
            return res.text.strip()
        return res.status_code
    
    def get_usernames(self, chat_session_id: int) -> List[str]:
        usernames = [attendee.username for attendee in self._attendees[chat_session_id].values() if not attendee.has_left]
        return usernames

    async def new_game(self, username: str) -> None:
        url = 'https://perso-etudiant.u-pem.fr/~rabah.cherak/LexiQuest-bis/multi/new_game.php'
        query = {'user': username}
        res = requests.post(url, query, headers={"User-Agent": "Firefox/12.0"})
        if res.status_code == 200:
            if res.text:
                response_json = res.json()  # Parse the response as JSON
                if 'state' in response_json:  # Check if the 'result' key exists
                    if 'id' in response_json:
                        if 'nodes' in response_json:
                            return response_json['state'],response_json['id'], response_json['nodes'], response_json
        return res.status_code
        # Iterate over all attendees in the chat session
        """
        for attendee_id in self._attendees[chat_session_id]:
            return await self.on_chat_message(chat_session_id, attendee_id, "ooo") 
        """

    async def add_word(self, chat_game_id: int, word: str, user: str, nodes: json) :
        url = 'https://perso-etudiant.u-pem.fr/~rabah.cherak/LexiQuest-bis/multi/add_word.php'
        query = {'gameId': chat_game_id, 'userWord': word, 'username': user, 'nodes': json.dumps(nodes)}
        res = requests.post(url, query, headers={"User-Agent": "Firefox/12.0"})
        if res.status_code == 200:
            if res.text:
                response_json = res.json()  # Parse the response as JSON
                if 'state' in response_json:
                    if 'nodes' in response_json:
                        return response_json['state'], response_json['nodes'], response_json
        return res.status_code

        

    async def check_id(self, chat_session_id:int):  
        return 0          


class UppercaseChatHooks(ChatHooks):
    """
    Version of chat hooks that relays all the chat messages in uppercase
    """
    async def on_chat_message(self, chat_session_id: int, sender_id: int, content: Any) -> Dict[int, Any]:
        result = super(self).on_chat_message(chat_session_id, sender_id, content)

        def to_upper(s):
            return s.upper() if isinstance(s, str) else s
            
        return {k: to_upper(v) for (k, v) in result.items()}
    
