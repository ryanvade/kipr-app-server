import { Component } from '@angular/core';
import { NavController } from 'ionic-angular';
import { JudgingPage } from '../judging/judging';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

  constructor(public navCtrl: NavController) {

  }

  judging(){
    this.navCtrl.push(JudgingPage,{})
  }

}
