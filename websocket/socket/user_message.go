package socket

import "encoding/json"

type UserMessage struct {
}

func (userMessage UserMessage) Execute(hub *Hub, message *WSMessage) {

	var innerMessage map[string]interface{}

	err := json.Unmarshal([]byte(message.Message), &innerMessage)
	if err != nil {
		return
	}
	messageType := innerMessage["type"].(string)

	for client := range hub.Clients {
		if (message.UserId != 0 && client.UserId == message.UserId) || (message.SessionId != "" && client.SessionId == message.SessionId) {
			if _, ok := client.SubscribeMessageTypeMap[messageType]; ok  {
				client.Send <- *message
			}
		}
	}

}
