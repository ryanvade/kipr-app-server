import { Injectable } from '@angular/core';
import { Http, Headers, RequestOptions } from '@angular/http';
import 'rxjs/add/operator/map';

/*
  Generated class for the StatusProvider provider.

  See https://angular.io/guide/dependency-injection for more info on providers
  and Angular DI.
*/
@Injectable()
export class StatusProvider {

  constructor(public http: Http) {
    console.log('Hello StatusProvider Provider');
  }

  async validateAuthToken(token, serverUrl) {
    let url = serverUrl + '/api/auth/status';
    let headers = new Headers();
    headers.append('Authorization', 'Bearer ' + token);
    const response = await this.http.get(url, new RequestOptions({headers: headers})).toPromise();
    try {
      let status = response.json().status;
      if (status == 'success') {
        return true;
      }
      return false;
    }catch(e) {
      return false;
    }
  }
}
