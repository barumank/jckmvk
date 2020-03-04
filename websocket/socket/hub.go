package socket

import (
	"encoding/json"
	"fmt"
	"github.com/streadway/amqp"
	"websocket/library"
)

// Hub maintains the set of active Clients and broadcasts wsmessages to the
// Clients.
type Hub struct {
	// Inbound wsmessages from the Clients.
	Broadcast chan WSMessage
	// Register requests from the Clients.
	Register chan *Client
	// Unregister requests from Clients.
	Unregister chan *Client
	// сообщения от воркеров
	BackendResponse chan amqp.Delivery
	// сообщения к воркерам
	BackendRequest chan WSMessage

	RabbitService *RabbitService

	// Registered Clients.
	Clients  map[*Client]bool
	Settings library.Settings
}

func NewHub() *Hub {
	return &Hub{
		Broadcast:       make(chan WSMessage, 30),
		Register:        make(chan *Client),
		Unregister:      make(chan *Client),
		BackendResponse: make(chan amqp.Delivery, 30),
		BackendRequest:  make(chan WSMessage, 30),
		Clients:         make(map[*Client]bool),
		Settings:        library.Settings{},
	}
}

func (hub *Hub) Run() {

	messageHandler := MessageHandler{
		Hub: hub,
	}

	for {
		select {
		case wsMessage := <-hub.BackendRequest:
			if message, err := json.Marshal(wsMessage); err == nil {
				hub.RabbitService.Publish(message)
			}
		case amqpMessage := <-hub.BackendResponse:

			wsMessage := WSMessage{}
			err := json.Unmarshal(amqpMessage.Body, &wsMessage)
			if err != nil {
				break
			}
			messageHandler.handleBackendResponse(&wsMessage)

		case client := <-hub.Register:
			hub.Clients[client] = true
			hub.BackendRequest <- WSMessage{
				Type:      TYPE_GET_USER_AUTH,
				SessionId: client.SessionId,
				UserId:    client.UserId,
			}
			fmt.Println("open")
		case client := <-hub.Unregister:
			if _, ok := hub.Clients[client]; ok {
				delete(hub.Clients, client)
				close(client.Send)
			}

			fmt.Println("Close new")
		case message := <-hub.Broadcast:
			messageHandler.handleSocketRequest(&message)
		}
	}
}
