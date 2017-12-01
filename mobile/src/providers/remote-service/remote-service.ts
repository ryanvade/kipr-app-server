import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/catch';

/*
  Generated class for the RemoteServiceProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class RemoteServiceProvider {

  constructor(public http: Http) {
    console.log('Hello RemoteServiceProvider Provider');
  }

  getRules(){
    return this.http.get("assets/json/events.json")
      .map(res => res.json().events);
      //.do((res : Response) => console.log(res.json()))
      //.catch(error => error);
  }

}
