<!DOCTYPE 
 <html> 
 <head> <meta charset="UTF-8">
  <style> body { font-family: sans-serif; font-size: 12px; color: 
  #1C1E1B; } h1 { font-size: 18px; color: 
  #0F3D2E; margin-bottom: 4px; } .periode { color:
 #5B6660; font-size: 11px; margin-bottom: 20px; } 
 table { width: 100%; border-collapse: collapse; margin-top: 10px; } th, td { border: 1px solid 
 #E4E9E1; padding: 6px 8px; text-align: left; } th { background:
  #0F3D2E; color: 
  #fff; } .totaux { margin-top: 20px; width: 300px; } .totaux 
  td { border: none; padding: 4px 8px; } .totaux .label 
  { color:
   #5B6660; } .totaux .solde 
   { font-weight: bold; font-size: 14px; color: 
   #0F3D2E; border-top: 2px solid 
   #0F3D2E; } .entree { color: #2F6B4F; } .sortie { color: 
   #A6453D; } </style> </head> <body> <h1>Relevé de caisse</h1> 
   <p class="periode">Période : du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</p> 
   <table> 
   <thead> 
   <tr><th>Date</th>
   <th>Type</th>
   <th>Libellé</th>
   <th>Catégorie</th>
   <th>Montant</th></tr>
    </thead> 
    <tbody> @foreach($operations as $op) 
    <tr> <td>{{ \Carbon\Carbon::parse($op->date)->format('d/m/Y') }}</td>
     <td>{{ $op->type === 'entree' ? 'Entrée' : 'Sortie' }}</td>
      <td>{{ $op->libelle }}</td>
       <td>{{ $op->categorie_nom ?? '' }}</td> 
       <td class="{{ $op->type }}">{{ $op->type === 'entree' ? '+' : '-' }}{{ number_format($op->montant, 0, ',', ' ') }} FCFA</td> </tr> @endforeach 
       </tbody>
        </table> 
        <table class="totaux">
         <tr><td class="label">Total entrées</td>
         <td>{{ number_format($totalEntrees, 0, ',', ' ') }} FCFA</td></tr> 
         
         <tr><td class="label">Total sorties validées</td>
         <td>{{ number_format($totalSorties, 0, ',', ' ') }} FCFA</td></tr> 
         <tr class="solde"><td>Solde net</td>
         <td>{{ number_format($totalEntrees - $totalSorties, 0, ',', ' ') }} FCFA</td></tr> 
         </table> 
         </body> 
         </html>