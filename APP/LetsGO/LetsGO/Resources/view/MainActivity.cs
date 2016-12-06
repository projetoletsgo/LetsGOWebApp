using System;
using Android.App;
using Android.Content;
using Android.Runtime;
using Android.Views;
using Android.Widget;
using Android.OS;
using System.Threading;
using System.Net;
using System.Collections.Specialized;
using System.Text;
using Newtonsoft.Json;
using LetsGO.Resources.model;
using LetsGO.Resources;

namespace LetsGO
{
    [Activity(Label = "LetsGO", MainLauncher = true, Icon = "@drawable/icon")]
    public class MainActivity : Activity
    {
        private Button mbtnSignUp;
        private Button btnLogin;
        private ProgressBar mbarraProgresso;
        private EditText txtEmail;
        private EditText txtSenha;

        protected override void OnCreate(Bundle bundle)
        {
            base.OnCreate(bundle);
            // Set our view from the "main" layout resource
            SetContentView(Resource.Layout.Main);

            mbarraProgresso = FindViewById<ProgressBar>(Resource.Id.pgbStatus);
            mbtnSignUp = FindViewById<Button>(Resource.Id.btnSignUp);
            mbtnSignUp.Click += (object sender, EventArgs e) =>
            {
                FragmentTransaction transaction = FragmentManager.BeginTransaction();
                DialogSignUp signUpDialog = new DialogSignUp();
                signUpDialog.Show(transaction, "dialog fragment");
                signUpDialog.mOnCadastrar += SignUpDialog_mOnCadastrar;
            };

            txtEmail = FindViewById<EditText>(Resource.Id.txtEmail);
            txtSenha = FindViewById<EditText>(Resource.Id.txtPassword);
            btnLogin = FindViewById<Button>(Resource.Id.btnLogin);
            btnLogin.Click += (object sender, EventArgs e) =>
            {
                if (txtEmail.Text.Length > 0 && txtSenha.Text.Length > 0)
                {
                    mbarraProgresso.Visibility = ViewStates.Visible;
                    WebClient client = new WebClient();
                    Uri uri = new Uri("http://192.168.0.2:8081/LetsGO/LoginJson.php");
                    NameValueCollection parameters = new NameValueCollection();

                    parameters.Add("tipo", "logar");
                    parameters.Add("s_email", txtEmail.Text);
                    parameters.Add("s_senha", txtSenha.Text);

                    client.UploadValuesCompleted += client_LoginCompleto;
                    client.UploadValuesAsync(uri, parameters);

                    mbarraProgresso.Visibility = ViewStates.Invisible;
                }
            };
        }

        void SignUpDialog_mOnCadastrar(object sender, CadastrarEvento e)
        {
            mbarraProgresso.Visibility = ViewStates.Visible;
            //insere usuario
            WebClient client = new WebClient();
            Uri uri = new Uri("http://192.168.0.2:8081/LetsGO/CadastrarUsuario.php");
            NameValueCollection parameters = new NameValueCollection();

            parameters.Add("Nome", e.Nome);
            parameters.Add("SobreNome", e.SobreNome);
            parameters.Add("Email", e.Email);
            parameters.Add("Senha", e.Senha);

            client.UploadValuesCompleted += client_UploadValuesCompleted;
            client.UploadValuesAsync(uri, parameters);

            mbarraProgresso.Visibility = ViewStates.Invisible;
        }


        void client_UploadValuesCompleted(object sender, UploadValuesCompletedEventArgs e)
        {
            string id = Encoding.UTF8.GetString(e.Result); //Get the data echo backed from PHP
            Toast.MakeText(ApplicationContext, "Cadastrado com sucesso! ID: " + id, ToastLength.Long).Show();
        }

        void client_LoginCompleto(object sender, UploadValuesCompletedEventArgs e)
        {
            string resultado = Encoding.UTF8.GetString(e.Result); //Get the data echo backed from PHP
            
            if (resultado.Length > 0)
            {
                Intent telaViagens = new Intent(this, typeof(TelaViagens));
                telaViagens.PutExtra("Usuario", resultado);
                StartActivity(telaViagens);    
            } else
            {
                Toast.MakeText(ApplicationContext, "Falha no login! Dados incorretos!", ToastLength.Long).Show();
            }
        }
    }
}


