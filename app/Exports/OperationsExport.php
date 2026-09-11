<?php
 namespace App\Exports; 
 use Maatwebsite\Excel\Concerns\FromCollection; 
 use Maatwebsite\Excel\Concerns\WithHeadings; 
 use Maatwebsite\Excel\Concerns\WithMapping; 
 
 class OperationsExport implements FromCollection, WithHeadings, WithMapping { protected $operations; 
 
public function __construct($operations) { 
                           $this->operations = $operations; 
                                           } 
                                           
public function collection(): \Illuminate\Support\Collection
{
    return $this->operations;
}
     
    
public function headings(): array { 
        return ['Date', 'Type', 'Libellé', 'Catégorie', 'Montant (FCFA)', 'Statut'];
                                      } 
                                      
public function map($operation): array { 
         return [ \Carbon\Carbon::parse($operation->date)->format('d/m/Y'), $operation->type === 'entree' ? 'Entrée' : 'Sortie', $operation->libelle, $operation->categorie_nom ?? '', $operation->montant, $operation->statut ?? '', 
                ]; 
                                      } }