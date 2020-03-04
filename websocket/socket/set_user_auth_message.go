package socket

import (
	"encoding/json"
)

type SetUserAuthMessage struct {
	IsIdentity bool  `json:"isIdentity"`
	UserId     int64 `json:"userId"`
}

func ( m SetUserAuthMessage)Execute(hub *Hub, message *WSMessage) {

	err:=json.Unmarshal([]byte(message.Message),&m)
	if err !=nil {
		return
	}
	if !m.IsIdentity {
		return
	}
	for client :=range hub.Clients{
		if client.SessionId == message.SessionId {
			client.UserId = m.UserId
		}
	}
}