import React, { Component } from "react";
import Hand from "./Hand";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Button from "react-bootstrap/Button";

export default class Poker extends Component {
  constructor(props) {
    super(props);
    this.state = { matchData: {} };
  }

  loadHands() {
    fetch(`https://desafio-asc-php.000webhostapp.com/`)
      .then(res => res.json())
      .then(res => this.setState({ matchData: res }));
  }

  componentDidMount() {
    this.loadHands();
  }

  getSuitName(suit) {
    let suitName = "";
    switch (suit) {
      case "O":
        suitName = "Ouros";
        break;
      case "C":
        suitName = "Copas";
        break;
      case "E":
        suitName = "Espadas";
        break;
      case "P":
        suitName = "Paus";
        break;
      default:
        suitName = suit;
    }
    return suitName;
  }

  getCardName(value) {
    let cardName;

    switch (value) {
      case "J":
        cardName = "Valete";
        break;
      case "Q":
        cardName = "Rainha";
        break;
      case "K":
        cardName = "Rei";
        break;
      case "A":
        cardName = "Ás";
        break;
      default:
        cardName = value;
    }

    return cardName;
  }

  render() {
    const { matchData } = this.state;
    const { players } = matchData;
    return (
      <Container>
        {players &&
          players.map(player => (
            <div style={{ margin: 50 }} key={player.name}>
              <Row style={{ justifyContent: "center" }}>
                <h1>
                  {player.name}
                  {player.winner === true ? ` 👑` : null}
                </h1>
              </Row>
              <Row style={{ justifyContent: "center" }}>
                {player.evaluate.hand_name ? (
                  <h1> {player.evaluate.hand_name} </h1>
                ) : (
                  <h1>
                    Carta mais alta:{" "}
                    {`${this.getCardName(player.evaluate.high_card.value)} de
                        ${this.getSuitName(player.evaluate.high_card.suit)}`}
                  </h1>
                )}
              </Row>
              <Row>
                <Col md={12}>{player.hand && <Hand hand={player.hand} />}</Col>
              </Row>
            </div>
          ))}
        <Row style={{ justifyContent: "center" }}>
          <Button
            variant="dark"
            style={{ margin: 25 }}
            onClick={() => this.loadHands()}
          >
            Jogar novamente ⭯
          </Button>
        </Row>
      </Container>
    );
  }
}
