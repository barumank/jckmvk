package socket

import (
	"github.com/streadway/amqp"
	"log"
	"time"
)

type RabbitService struct {
	Dsn     string
	Workers struct {
		Exchange     string
		ExchangeType string
		RoutingKey   string
	}
	Ws struct {
		Exchange     string
		ExchangeType string
		RoutingKey   string
		QueueName    string
	}
	connection *amqp.Connection
	channel    *amqp.Channel
	Hub        *Hub
}

func (rabbitService *RabbitService) reConnect() error {
	var err error

	rabbitService.connection, err = amqp.Dial(rabbitService.Dsn)
	count := 50
	for err != nil && count > 0 {
		time.Sleep(time.Second * 3)
		rabbitService.connection, err = amqp.Dial(rabbitService.Dsn)
		count--
	}

	if rabbitService.channel, err = rabbitService.connection.Channel(); err != nil {
		return err
	}

	return err
}

func (rabbitService *RabbitService) Connect() error {
	var err error

	if err = rabbitService.reConnect(); err != nil {
		return err
	}

	if err = rabbitService.channel.ExchangeDeclare(
		rabbitService.Workers.Exchange,     // name
		rabbitService.Workers.ExchangeType, // type
		true,                               // durable
		false,                              // auto-deleted
		false,                              // internal
		false,                              // noWait
		nil,                                // arguments
	); err != nil {
		return err
	}

	//потребитель вебcокета
	if err = rabbitService.channel.ExchangeDeclare(
		rabbitService.Ws.Exchange,     // name
		rabbitService.Ws.ExchangeType, // type
		true,                          // durable
		false,                         // delete when complete
		false,                         // internal
		false,                         // noWait
		nil,                           // arguments
	); err != nil {
		return err
	}

	queue, err := rabbitService.channel.QueueDeclare(
		rabbitService.Ws.QueueName, // name of the queue
		true,                       // durable
		false,                      // delete when unused
		false,                      // exclusive
		false,                      // noWait
		nil,                        // arguments
	)
	if err != nil {
		return err
	}

	if err = rabbitService.channel.QueueBind(
		queue.Name,                  // name of the queue
		rabbitService.Ws.RoutingKey, // bindingKey
		rabbitService.Ws.Exchange,   // sourceExchange
		false,                       // noWait
		nil,                         // arguments
	); err != nil {
		return err
	}

	 err = rabbitService.createConsumer()

	return err
}

func (rabbitService *RabbitService) createConsumer() error {

	deliveries, err := rabbitService.channel.Consume(
		rabbitService.Ws.QueueName, // name
		"",         // consumerTag,
		false,      // noAck
		false,      // exclusive
		false,      // noLocal
		false,      // noWait
		nil,        // arguments
	)
	if err != nil {
		return err
	}
	//запуск воркера
	go rabbitService.Handle(deliveries)

	return err
}

func (rabbitService *RabbitService) Publish(message []byte) error {

	if rabbitService.connection.IsClosed() {
		if err := rabbitService.reConnect(); err != nil {
			return err
		}
		if err := rabbitService.createConsumer(); err != nil {
			return err
		}
	}

	return rabbitService.channel.Publish(
		rabbitService.Workers.Exchange,   // publish to an exchange
		rabbitService.Workers.RoutingKey, // routing to 0 or more queues
		false,                            // mandatory
		false,                            // immediate
		amqp.Publishing{
			Headers:         amqp.Table{},
			ContentType:     "text/plain",
			ContentEncoding: "",
			Body:            message,
			DeliveryMode:    amqp.Transient, // 1=non-persistent, 2=persistent
			Priority:        0,              // 0-9
			// a bunch of application/implementation-specific fields
		},
	)
}

func (rabbitService *RabbitService) Handle(deliveries <-chan amqp.Delivery) {
	for d := range deliveries {
		rabbitService.Hub.BackendResponse <- d
		d.Ack(false)
	}
	log.Printf("handle: deliveries channel closed")
}

func (rabbitService *RabbitService) Close() {
	_ = rabbitService.connection.Close()
}
