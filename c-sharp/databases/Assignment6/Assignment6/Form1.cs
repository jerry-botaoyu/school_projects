using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

//BoTao Yu
//ID: 1736204
namespace Assignment6
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void cityBindingNavigatorSaveItem_Click(object sender, EventArgs e)
        {
            this.Validate();
            this.cityBindingSource.EndEdit();
            this.tableAdapterManager.UpdateAll(this.populationDBDataSet);

        }

        private void Form1_Load(object sender, EventArgs e)
        {
            // TODO: This line of code loads data into the 'populationDBDataSet.City' table. You can move, or remove it, as needed.
            this.cityTableAdapter.Fill(this.populationDBDataSet.City);

        }

        //Populate the table with the population sort by ascending order
        private void sortByPopulationAsceButton_Click(object sender, EventArgs e)
        {
            this.cityTableAdapter.FillByPopulationAsce(this.populationDBDataSet.City);
        }

        //Populate the table with the population sort by descending order
        private void sortByPopulationDescButton_Click(object sender, EventArgs e)
        {
            this.cityTableAdapter.FillByPopulationDesc(this.populationDBDataSet.City);
        }

        //Populate the table with the City Name sort by ascending order
        private void sortByCityNameButton_Click(object sender, EventArgs e)
        {
            this.cityTableAdapter.FillByCityNameAsce(this.populationDBDataSet.City);
        }

        //Show the total population in MessageBox 
        private void totalPopulationButton_Click(object sender, EventArgs e)
        {
            MessageBox.Show("The total Population is " + cityTableAdapter.TotalPopulation()?.ToString("#,##0"));
        }

        //Show the average population in MessageBox 
        private void averagePopulationButton_Click(object sender, EventArgs e)
        {
            var averagePopulation = String.Format("{0:N0}", cityTableAdapter.AveragePopulation());
            MessageBox.Show("The average population is " + averagePopulation);
        }

        //Show the highest population in MessageBox 
        private void highestPopulationButton_Click(object sender, EventArgs e)
        {
            MessageBox.Show("The highest population is " + cityTableAdapter.HighestPopulation()?.ToString("#,##0"));
        }

        //Show the lowest population in MessageBox 
        private void lowestPopulationButton_Click(object sender, EventArgs e)
        {
            MessageBox.Show("The lowest population is " + cityTableAdapter.LowestPopulation()?.ToString("#,##0"));
        }

       
    }
}
