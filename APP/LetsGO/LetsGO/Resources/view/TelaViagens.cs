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
using Newtonsoft.Json;
using LetsGO.Resources.view;

namespace LetsGO.Resources
{
    [Activity(Label = "Viagens")]
    class TelaViagens : Activity
    {
        private List<Excursao> excursoes;
        private ListView lstViagens;
        protected override void OnCreate(Bundle bundle)
        {
            base.OnCreate(bundle);
            SetContentView(Resource.Layout.telaViagens);

            Usuario usuario = JsonConvert.DeserializeObject<Usuario>(Intent.GetStringExtra("Usuario") ?? "Data not available");
            excursoes = new List<Excursao>();
            excursoes.Add(new Excursao("Olimpia", usuario, null, 75.00f, 1));
            excursoes.Add(new Excursao("Rio de Janeiro", usuario, null, 230.00f, 1));
            excursoes.Add(new Excursao("Campos do Jordão", usuario, null, 128.00f, 1));

            lstViagens = FindViewById<ListView>(Resource.Id.lstViagens);

            ArrayAdapter<Excursao> adpExcursao = new ArrayAdapter<Excursao>(this, Resource.Layout.InformacaoViagens, excursoes);

            lstViagens.Adapter = adpExcursao;
            lstViagens.ItemClick += click_lstViagens;

        }


        void click_lstViagens(object sender, AdapterView.ItemClickEventArgs e)
        {
            String excursao = JsonConvert.SerializeObject(excursoes[e.Position]);
            Intent detalhesViagens = new Intent(this, typeof(DetalhesViagens));
            detalhesViagens.PutExtra("Excursao", excursao);
            StartActivity(detalhesViagens);
        }


    }
}