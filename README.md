# yii2-auditrail

Yii2 edition of the [audittrail extension](https://github.com/Sammaye/audittrail).

There is only one thing that has changed since the previous version which is the ideal way to get records.

The below example is the one from the previous documentation rewritten for Yii2:

    <?php
    use yii\data\ActiveDataProvider;
    use sammaye\auditrail\AuditTrail;
    use common\models\Title;
    use common\models\Product;

    $model_ids = array(array($model->id, Title::className()));
    foreach($model->products as $id => $product){
        $model_ids[] = array($product->id, Product::className());
    }

    $criteria = AuditTrail::find();
    $param_id = 0;
    
    // $model_ids is the one you built in your original code
    foreach( $model_ids as $id_pair ) {
        $criteria->orWhere('model_id = :id' . $param_id . ' AND model = :model' . $param_id);
        $criteria->addParams([
            ':id' . $param_id => $id_pair[0], 
            ':model' . $param_id => $id_pair[1]
        ]);
        $param_id++;
    }
    $criteria->orderBy(['stamp' => SORT_DESC]);

    echo yii\grid\GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $criteria,
            'pagination' => [
                'pageSize' => 100,
            ]
        ]),
        'columns' => [
            [
                'label' => 'Author',
                'value' => function($model, $index, $widget){
                    return $model->user ? $model->user->email : "";
                }
            ],
            [
                'attribute' => 'model',
                'value' => function($model, $index, $widget){
                    $p = explode('\\', $model->model);
                    return end($p);
                }
            ],
            'model_id',
            'action',
            [
                'label' => 'field',
                'value' => function($model, $index, $widget){
                    return $model->getParent()->getAttributeLabel($model->field);
                }
            ],
            'old_value',
            'new_value',
            [
                'label' => 'Date Changed',
                'value' => function($model, $index, $widget){
                    return date("d-m-Y H:i:s", strtotime($model->stamp));
                }
            ]
        ]
    ]); ?>

Notice the:
    
    [
        'attribute' => 'model',
        'value' => function($model, $index, $widget){
            $p = explode('\\', $model->model);
            return end($p);
        }
    ],
    
This allows us to get the name of the model without the namespace part on it.

Currently in this extension I store the fully qualified name of the class to:

- Take advantage of namespacing to create uniqueness
- To make it easier to query using the `className()` function in Objects.

# Todo:

- Test the migration

# Resources:

- [Packgist](https://packagist.org/packages/sammaye/yii2-auditrail)
- [Issue Tracker](https://github.com/Sammaye/yii2-auditrail/issues)