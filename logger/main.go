package main

import (
	"encoding/json"
	"io/ioutil"
	"sync"
)

func main() {

	response, err := ioutil.ReadFile("settings.json")
	if err != nil {
		return
	}
	settings := Settings{}
	err = json.Unmarshal(response, &settings)
	if err != nil {
		return
	}

	messageChannel := make(chan LoggerMessage, 100)
	progressSocket := make(chan bool)
	closeSocket := make(chan bool)
	progressDbHub := make(chan bool)
	closeDbHub := make(chan bool)

	var waitGroup sync.WaitGroup

	socket := TcpSocket{
		Settings:  settings,
		Progress:  progressSocket,
		Close:     closeSocket,
		WaitGroup: &waitGroup,
		OutLog:    messageChannel,
	}
	waitGroup.Add(1)
	go socket.Run()
	dbHub := DbHub{
		Progress:  progressDbHub,
		Close:     closeDbHub,
		WaitGroup: &waitGroup,
		InLog:     messageChannel,
		Settings:  settings,
	}
	waitGroup.Add(1)
	go dbHub.Run()
	waitGroup.Add(1)
	go func() {
	exit:
		for {
			select {
			case <-closeSocket:
				close(progressDbHub)
				break exit
			case <-closeDbHub:
				close(progressSocket)
				break exit
			}
		}
		waitGroup.Done()
	}()
	waitGroup.Wait()
}

//func main() {
//	fmt.Println("Hello World!")
//
//	//Basic variables
//	port := ":8084"
//	protocol := "udp"
//
//	//Build the address
//	udpAddr, err := net.ResolveUDPAddr(protocol, port)
//	if err != nil {
//		fmt.Println("Wrong Address")
//		return
//	}
//
//	//Output
//	fmt.Println("Coded by Roberto E. Zubieta\nReading " + protocol + " from " + udpAddr.String())
//
//	//Create the connection
//	udpConn, err := net.ListenUDP(protocol, udpAddr)
//	if err != nil {
//		fmt.Println(err)
//	}
//
//	//Keep calling this function
//	var i int64 = 1
//	for {
//		display(udpConn)
//		i++
//		fmt.Println(i)
//	}
//
//}
//
//func display(conn *net.UDPConn) {
//
//	buf := make([]byte, 2000)
//	_, err := conn.Read(buf)
//	if err != nil {
//		fmt.Println("Error Reading")
//		return
//	} else {
//		fmt.Println(string(buf))
//		fmt.Println("Package Done")
//	}
//
//}
