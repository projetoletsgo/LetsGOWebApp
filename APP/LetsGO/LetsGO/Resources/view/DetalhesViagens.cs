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

namespace LetsGO.Resources.view
{
    [Activity(Label = "DetalhesViagens")]
    public class DetalhesViagens : Activity
    {
        TextView txtExcursao;
        TextView txtValor;

        protected override void OnCreate(Bundle savedInstanceState)
        {
            base.OnCreate(savedInstanceState);
            SetContentView(Resource.Layout.detalhes_viagens);

            Excursao ex = JsonConvert.DeserializeObject<Excursao>(Intent.GetStringExtra("Excursao") ?? "Data not available");

            txtExcursao = FindViewById<TextView>(Resource.Id.txtExcursao);
            txtValor = FindViewById<TextView>(Resource.Id.txtValor);

            txtExcursao.Text = "Excursão: " + ex.Nome.ToString();
            txtValor.Text = "Valor: " + ex.Preco.ToString();

        }
    }
}