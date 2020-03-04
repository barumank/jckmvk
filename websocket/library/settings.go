package library

type Settings struct {
	Port      string `json:"port"`
	RabbitDsn string `json:"rabbitDsn"`

	Workers struct {
		Exchange     string `json:"exchange"`
		ExchangeType string `json:"exchangeType"`
		RoutingKey   string `json:"routingKey"`
	} `json:"workers"`
	Ws struct {
		Exchange     string `json:"exchange"`
		ExchangeType string `json:"exchangeType"`
		RoutingKey   string `json:"routingKey"`
		QueueName    string `json:"queueName"`
	} `json:"ws"`
}
