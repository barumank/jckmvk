package main

import (
	"bufio"
	"encoding/json"
	"log"
	"net"
	"strconv"
	"sync"
)

type TcpSocket struct {
	Settings  Settings
	OutLog    chan LoggerMessage
	Progress  chan bool
	Close     chan bool
	WaitGroup *sync.WaitGroup
}

func (socket *TcpSocket) Run() {
	defer socket.WaitGroup.Done()

	addr := ":" + strconv.Itoa(socket.Settings.Socket.Port)
	log.Printf("starting server on %v\n", addr)
	listener, err := net.Listen("tcp", addr)
	if err != nil {
		close(socket.Close)
		return
	}
	var wg sync.WaitGroup
	defer func() {
		listener.Close()
		wg.Wait()
	}()

	doneCon := make(chan bool)
	for {
		select {
		case <-socket.Progress:
			close(doneCon)
			return
		default:
			conn, err := listener.Accept()
			if err != nil {
				log.Printf("error accepting connection %v", err)
				continue
			}
			log.Printf("accepted connection from %v", conn.RemoteAddr())
			wg.Add(1)
			go socket.handle(conn, doneCon, &wg)
		}

	}
}

func (socket *TcpSocket) handle(conn net.Conn, doneCon chan bool, wg *sync.WaitGroup) error {
	defer func() {
		log.Printf("closing connection from %v", conn.RemoteAddr())
		conn.Close()
		wg.Done()
	}()
	r := bufio.NewReader(conn)
	scanr := bufio.NewScanner(r)
	for {
		select {
		case <-doneCon:
			return nil
		default:
			scanned := scanr.Scan()
			if !scanned {
				if err := scanr.Err(); err != nil {
					log.Printf("%v(%v)", err, conn.RemoteAddr())
					return err
				}
				return nil
			}
			var logMessage LoggerMessage
			err := json.Unmarshal([]byte(scanr.Text()), &logMessage)
			if err != nil {
				log.Printf("json %v", err)
				return err
			}
			socket.OutLog <- logMessage
		}

	}

}
