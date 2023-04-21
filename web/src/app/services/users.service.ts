import { HttpClient, HttpHeaders,HttpResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { environment } from '../../environments/environment';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
@Injectable({
  providedIn: 'root',
})
export class UsersService {
  private getUsersListDataURL =
    environment.apiUrl + 'api/users/getUsersListData';
  private createUserURL = environment.apiUrl + 'api/users/create';
  httpOptions = {
    headers: new HttpHeaders({ 'Content-Type': 'application/json' }),
  };
  constructor(private route: ActivatedRoute, private httpClient: HttpClient) {}

  // get user data from laravel client call
  getUsersListData(): Observable<any> {
    //get method
    return this.httpClient
      .get<any>(this.getUsersListDataURL, this.httpOptions)
      .pipe(
        map((data) => {
          return data;
        })
      );
  }

  // create user data to laravel client call
  createUser(params: any): Observable<any> {
    // check phone and change exact format
    if (params.phone) {
      var phoneFormat = params.phone;
      var first = phoneFormat.substring(0, 3);
      var second = phoneFormat.substring(3, 6);
      var third = phoneFormat.substring(6, 10);
      params.phone = '(' + first + ')' + ' ' + second + '-' + third;
    }

    //post method
    return this.httpClient
      .post<any>(this.createUserURL, params, this.httpOptions)
      .pipe(
        map((data:any) => {
          return data;
        })
      );
  }
}
