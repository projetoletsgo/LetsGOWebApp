using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using Android.App;
using Android.Content;
using Android.OS;
using Android.Runtime;
using Android.Views;
using Android.Widget;
using LetsGO.Resources.model;

namespace LetsGO
{
    public class CadastrarEvento : EventArgs
    {
        
        public string Nome { get; set; }
        public string SobreNome { get; set; }
        public string Email { get; set; }
        public string Senha { get; set; }

        public CadastrarEvento(string nome, string sobrenome, string email, string senha) : base()
        {
            Nome = nome;
            SobreNome = sobrenome;
            Email = email;
            Senha = senha;
        }

    }

    class DialogSignUp : DialogFragment
    {
        private EditText mtxtNome;
        private EditText mtxtSobreNome;
        private EditText mtxtEmail;
        private EditText mtxtPassword;
        private Button mbtnCadastrar;

        public event EventHandler<CadastrarEvento> mOnCadastrar;
            
        public override View OnCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState)
        {
            base.OnCreateView(inflater, container, savedInstanceState);
            var view = inflater.Inflate(Resource.Layout.dialog_sign_up, container, false);
            mtxtNome = view.FindViewById<EditText>(Resource.Id.txtFirstName);
            mtxtSobreNome = view.FindViewById<EditText>(Resource.Id.txtLastName);
            mtxtEmail = view.FindViewById<EditText>(Resource.Id.txtEmail);
            mtxtPassword = view.FindViewById<EditText>(Resource.Id.txtPassword);
            mbtnCadastrar = view.FindViewById<Button>(Resource.Id.btnCadastrar);

            mbtnCadastrar.Click += mbtnCadastrar_Click;

            return view;
        }

        private void mbtnCadastrar_Click(object sender, EventArgs e)
        {

            mOnCadastrar.Invoke(this, new CadastrarEvento(mtxtNome.Text, mtxtSobreNome.Text, mtxtEmail.Text, mtxtPassword.Text));
            this.Dismiss();

        }
    }
}