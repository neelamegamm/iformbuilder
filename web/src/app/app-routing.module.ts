import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { UserCreateComponent } from './users/user-create/user-create.component';
import { UserListComponent } from './users/user-list/user-list.component';
import { TranslateLangComponent } from './translate-lang/translate-lang.component';
const routes: Routes = [{
  path: '',
  redirectTo: '/users',
  pathMatch: 'full'
},
{
  path: 'users',
  component: UserListComponent
},
{
  path: 'user-create',
  component: UserCreateComponent
},
{
  path: 'translate',
  component: TranslateLangComponent
},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
