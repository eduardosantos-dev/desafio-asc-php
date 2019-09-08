import React, { Component } from "react";
import "./App.css";
import Board from "./Board";
import Ranking from "./Ranking";
import Navbar from "react-bootstrap/Navbar";
import Nav from "react-bootstrap/Nav";
import { BrowserRouter as Router, Route, Link } from "react-router-dom";

export default class Menu extends Component {
  render() {
    return (
      <Router>
        <Navbar bg="dark" variant="dark">
          <Navbar.Brand as={Link} to="/">
            <img
              alt=""
              src="/poker-hand.svg"
              width="30"
              height="30"
              className="d-inline-block align-top"
            />
            {" ASC Poker"}
          </Navbar.Brand>
          <Nav className="mr-auto">
            <Nav.Link as={Link} to="/">
              Jogar
            </Nav.Link>
            <Nav.Link as={Link} to="/ranking">
              Ranking
            </Nav.Link>
          </Nav>
        </Navbar>
        <Route path="/" exact component={Board} />
        <Route path="/ranking" exact component={Ranking} />
      </Router>
    );
  }
}
